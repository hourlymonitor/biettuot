<?php

namespace App\Http\Controllers;

use App\Models\ContentPost;
use App\Models\ContentReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        $queue = ContentPost::with('user')->where('status', 'draft')->latest('updated_at')->paginate(20);
        return view('moderation.index', compact('queue'));
    }

    public function show(ContentPost $contentPost): View
    {
        return view('moderation.show', ['contentPost' => $contentPost->load('user'), 'reviews' => $contentPost->reviews()->with('reviewer')->latest()->get()]);
    }

    public function approve(Request $request, ContentPost $contentPost): RedirectResponse
    {
        $validated = $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);
        DB::transaction(function () use ($contentPost, $validated): void {
            $post = ContentPost::whereKey($contentPost->id)->lockForUpdate()->firstOrFail();
            abort_unless($post->status === 'draft', 409);
            $post->update(['status' => 'approved']);
            $post->reviews()->create(['reviewer_user_id' => auth()->id(), 'status' => 'approved', 'notes' => $validated['notes'] ?? 'Approved by moderator']);
            $post->user->pointLedger()->create(['points' => 50, 'reason' => 'Approved content: '.$post->title, 'source' => 'moderation_approval']);
        });
        return redirect()->route('moderation.index')->with('status', 'Bài viết đã được duyệt và cộng 50 điểm cho tác giả.');
    }

    public function reject(Request $request, ContentPost $contentPost): RedirectResponse
    {
        $validated = $request->validate(['notes' => ['required', 'string', 'max:2000']]);
        DB::transaction(function () use ($contentPost, $validated): void {
            $post = ContentPost::whereKey($contentPost->id)->lockForUpdate()->firstOrFail();
            abort_unless($post->status === 'draft', 409);
            $post->update(['status' => 'rejected']);
            $post->reviews()->create(['reviewer_user_id' => auth()->id(), 'status' => 'rejected', 'notes' => $validated['notes']]);
        });
        return redirect()->route('moderation.index')->with('status', 'Bài viết đã bị từ chối.');
    }
}
