<?php

namespace App\Http\Controllers;

use App\Models\AiUsageLog;
use App\Models\ChatSession;
use App\Models\ContentPost;
use App\Models\ContentReview;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);
        $range = [$from, $to];

        $stats = [
            'new_users' => User::whereBetween('created_at', $range)->count(),
            'conversations' => ChatSession::whereBetween('created_at', $range)->count(),
            'content_posts' => ContentPost::whereBetween('created_at', $range)->count(),
            'pending_posts' => ContentPost::where('status', 'draft')->count(),
            'ai_calls' => AiUsageLog::whereBetween('created_at', $range)->count(),
            'ai_failures' => AiUsageLog::whereBetween('created_at', $range)->where('status', 'failed')->count(),
            'ai_avg_duration_ms' => (int) round((float) (AiUsageLog::whereBetween('created_at', $range)->avg('duration_ms') ?? 0)),
            'prompt_tokens' => (int) (AiUsageLog::whereBetween('created_at', $range)->sum('prompt_tokens') ?? 0),
            'completion_tokens' => (int) (AiUsageLog::whereBetween('created_at', $range)->sum('completion_tokens') ?? 0),
        ];

        $dailyUsers = User::query()
            ->whereBetween('created_at', $range)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')->orderBy('day')->pluck('total', 'day');
        $dailyAiCalls = AiUsageLog::query()
            ->whereBetween('created_at', $range)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')->orderBy('day')->pluck('total', 'day');

        $aiByProvider = AiUsageLog::query()
            ->whereBetween('created_at', $range)
            ->select('provider', 'model')
            ->selectRaw('COUNT(*) as calls, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as failures, AVG(duration_ms) as avg_duration_ms', ['failed'])
            ->groupBy('provider', 'model')->orderByDesc('calls')->get();

        $reviewStatuses = ContentReview::query()
            ->whereBetween('created_at', $range)
            ->select('status')->selectRaw('COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $topReviewers = ContentReview::query()
            ->join('users', 'users.id', '=', 'content_reviews.reviewer_user_id')
            ->whereBetween('content_reviews.created_at', $range)
            ->select('users.name', 'users.email')
            ->selectRaw('COUNT(*) as reviews')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('reviews')->limit(10)->get();

        $maxDaily = max(1, (int) max($dailyUsers->max() ?? 0, $dailyAiCalls->max() ?? 0));

        return view('admin.analytics', compact(
            'from', 'to', 'stats', 'dailyUsers', 'dailyAiCalls', 'aiByProvider',
            'reviewStatuses', 'topReviewers', 'maxDaily'
        ));
    }

    public function aiCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->dateRange($request);

        return response()->streamDownload(function () use ($from, $to): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['created_at', 'provider', 'model', 'status', 'duration_ms', 'prompt_tokens', 'completion_tokens', 'user_id', 'chat_session_id']);

            AiUsageLog::query()->whereBetween('created_at', [$from, $to])->orderBy('id')->chunk(500, function ($logs) use ($output): void {
                foreach ($logs as $log) {
                    fputcsv($output, [$log->created_at, $this->csvCell($log->provider), $this->csvCell($log->model), $log->status,
                        $log->duration_ms, $log->prompt_tokens, $log->completion_tokens, $log->user_id, $log->chat_session_id]);
                }
            });

            fclose($output);
        }, 'biettuot-ai-usage.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function moderationCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->dateRange($request);

        return response()->streamDownload(function () use ($from, $to): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['reviewed_at', 'post_id', 'title', 'author_email', 'reviewer_email', 'status', 'notes']);

            ContentReview::query()
                ->with(['contentPost.user', 'reviewer'])
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('id')->chunk(500, function ($reviews) use ($output): void {
                    foreach ($reviews as $review) {
                        fputcsv($output, [
                            $review->created_at,
                            $review->content_post_id,
                            $this->csvCell($review->contentPost?->title),
                            $this->csvCell($review->contentPost?->user?->email),
                            $this->csvCell($review->reviewer?->email),
                            $review->status,
                            $this->csvCell($review->notes),
                        ]);
                    }
                });

            fclose($output);
        }, 'biettuot-moderation-report.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** @return array{CarbonImmutable, CarbonImmutable} */
    private function dateRange(Request $request): array
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = isset($validated['from'])
            ? CarbonImmutable::parse($validated['from'])->startOfDay()
            : CarbonImmutable::today()->subDays(29)->startOfDay();
        $to = isset($validated['to'])
            ? CarbonImmutable::parse($validated['to'])->endOfDay()
            : CarbonImmutable::today()->endOfDay();

        return [$from, $to];
    }

    private function csvCell(?string $value): string
    {
        $value ??= '';

        // Prevent spreadsheet formula execution for untrusted text fields.
        return preg_match('/^[=+\-@\t\r]/u', $value) === 1 ? "'".$value : $value;
    }
}
