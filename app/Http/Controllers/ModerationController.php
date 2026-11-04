<?php

namespace App\Http\Controllers;

use App\Models\ContentPost;
use App\Models\ContentReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        $queue = ContentPost::query()
            ->where('status', 'draft')
            ->latest('updated_at')
            ->get();

        return view('moderation.index', compact('queue'));
    }

    public function show(ContentPost $contentPost): View
    {
        return view('moderation.show', [
            'contentPost' => $contentPost,
            'reviews' => $contentPost->reviews()->latest()->get(),
        ]);
    }

    public function approve(Request $request, ContentPost $contentPost): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $contentPost->update(['status' => 'approved']);
        $contentPost->reviews()->create([
            'reviewer_user_id' => auth()->id(),
            'status' => 'approved',
            'notes' => $validated['notes'] ?? 'Approved by moderator',
        ]);

        $contentPost->user->pointLedger()->create([
            'points' => 50,
            'reason' => 'Approved content: '.$contentPost->title,
            'source' => 'moderation_approval',
        ]);

        return redirect()->route('moderation.index')->with('status', 'Bài viết đã được duyệt và cộng 50 điểm cho tác giả.');
    }

    public function reject(Request $request, ContentPost $contentPost): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:2000'],
        ]);

        $contentPost->update(['status' => 'rejected']);
        $contentPost->reviews()->create([
            'reviewer_user_id' => auth()->id(),
            'status' => 'rejected',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('moderation.index')->with('status', 'Bài viết đã bị từ chối.');
    }
}
