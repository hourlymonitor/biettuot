<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ContentPost;
use App\Services\Ai\AiGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        $session = auth()->user()->chatSessions()->firstOrCreate([
            'title' => 'Draft generation',
        ]);

        $response = $gateway->complete([
            ['role' => 'user', 'content' => "Viết một bài ngắn, dễ hiểu, theo phong cách thân thiện và có thể đăng lên website. Chủ đề: {$validated['prompt']}\n\nYêu cầu: 3 phần: tiêu đề, 3-5 đoạn và kết luận. Không dùng markdown quá nhiều."],
        ], auth()->user(), $session);

        return redirect()->route('content.create')->with('draft', $response['content']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:20000'],
            'status' => ['nullable', 'in:draft,published'],
        ]);

        $post = auth()->user()->contentPosts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'draft',
            'source_prompt' => $validated['title'],
        ]);

        return redirect()->route('content.show', $post)->with('status', 'Bài viết đã được lưu.');
    }

    public function show(ContentPost $contentPost): View
    {
        abort_unless($contentPost->user_id === auth()->id(), 404);

        return view('content.show', compact('contentPost'));
    }
}
