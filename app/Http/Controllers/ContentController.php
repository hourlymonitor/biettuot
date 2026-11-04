<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ContentPost;
use App\Services\Ai\AiGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ContentController extends Controller
{
    public function index(): View
    {
        $posts = auth()->user()->contentPosts()->latest()->paginate(10);

        return view('content.index', compact('posts'));
    }

    public function create(): View
    {
        return view('content.create');
    }

    public function generate(Request $request, AiGateway $gateway): RedirectResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:2000'],
        ]);

        $session = auth()->user()->chatSessions()->create([
            'title' => 'Tạo bài: '.mb_substr(trim($validated['prompt']), 0, 70),
        ]);

        try {
            $response = $gateway->complete([
                [
                    'role' => 'user',
                    'content' => "Viết bản nháp bài viết tiếng Việt dễ hiểu, thân thiện. Trả lời theo cấu trúc: tiêu đề, nội dung có các đoạn ngắn, kết luận. Chủ đề: {$validated['prompt']}",
                ],
            ], auth()->user(), $session);

            return redirect()->route('content.create')->with('draft', $response['content'])->with('prompt', $validated['prompt']);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('content.create')
                ->withInput(['prompt' => $validated['prompt']])
                ->with('error', 'Không thể tạo bản nháp lúc này. Hãy thử lại sau.');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:20000'],
            'source_prompt' => ['nullable', 'string', 'max:2000'],
        ]);

        $post = auth()->user()->contentPosts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            // Publishing is intentionally disabled until moderation is implemented.
            'status' => 'draft',
            'source_prompt' => $validated['source_prompt'] ?? null,
        ]);

        return redirect()->route('content.show', $post)->with('status', 'Bản nháp đã được lưu.');
    }

    public function show(ContentPost $contentPost): View
    {
        abort_unless($contentPost->user_id === auth()->id(), 404);

        return view('content.show', compact('contentPost'));
    }
}
