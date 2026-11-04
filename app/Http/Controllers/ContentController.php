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
        $validated = $request->validate(['prompt' => ['required', 'string', 'max:2000']]);
        $user = $request->user();
        $session = $user->chatSessions()->create([
            'title' => 'Tạo bài: '.mb_substr(trim($validated['prompt']), 0, 70),
        ]);

        try {
            $result = $gateway->complete([[
                'role' => 'user',
                'content' => "Viết bản nháp bài viết tiếng Việt dễ hiểu, thân thiện. Trả lời theo cấu trúc: tiêu đề, các đoạn nội dung ngắn, kết luận. Chủ đề: {$validated['prompt']}",
            ]], $user, $session);

            return redirect()->route('content.create')
                ->with('draft', $result['content'])
                ->with('prompt', $validated['prompt']);
        } catch (Throwable $exception) {
            report($exception);
            return redirect()->route('content.create')->withInput()
                ->with('prompt', $validated['prompt'])
                ->with('error', 'Chưa thể tạo bản nháp. Kiểm tra cấu hình AI hoặc thử lại sau.');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:20000'],
            'source_prompt' => ['nullable', 'string', 'max:2000'],
        ]);

        $post = $request->user()->contentPosts()->create([
            'title' => $data['title'],
            'body' => $data['body'],
            'source_prompt' => $data['source_prompt'] ?? null,
            'status' => 'draft', // Only moderators can approve a draft.
        ]);

        return redirect()->route('content.show', $post)->with('status', 'Đã lưu bản nháp. Bài viết sẽ được moderator duyệt.');
    }

    public function show(ContentPost $contentPost): View
    {
        abort_unless($contentPost->user_id === auth()->id(), 404);
        return view('content.show', compact('contentPost'));
    }
}
