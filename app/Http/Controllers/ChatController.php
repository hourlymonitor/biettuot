<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Services\Ai\AiGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $sessions = auth()->user()->chatSessions()->latest('updated_at')->limit(20)->get();

        return view('chat.index', compact('sessions'));
    }

    public function show(ChatSession $chatSession): View
    {
        abort_unless($chatSession->user_id === auth()->id(), 404);

        return view('chat.show', [
            'chatSession' => $chatSession,
            'messages' => $chatSession->messages()->oldest()->get(),
            'sessions' => auth()->user()->chatSessions()->latest('updated_at')->limit(20)->get(),
        ]);
    }

    public function send(Request $request, AiGateway $gateway): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:12000'],
            'chat_session_id' => ['nullable', 'integer', 'exists:chat_sessions,id'],
        ]);

        $session = null;
        if (! empty($validated['chat_session_id'])) {
            $session = auth()->user()->chatSessions()->findOrFail($validated['chat_session_id']);
        }

        if (! $session) {
            $session = auth()->user()->chatSessions()->create([
                'title' => mb_substr(trim($validated['message']), 0, 80),
            ]);
        }

        $session->messages()->create([
            'role' => 'user',
            'content' => trim($validated['message']),
        ]);

        try {
            $messages = $session->messages()->oldest()->get()->map(fn (ChatMessage $message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])->all();

            $reply = $gateway->complete($messages, auth()->user(), $session);

            $session->messages()->create([
                'role' => 'assistant',
                'content' => $reply['content'],
                'metadata' => ['provider' => $reply['provider'], 'model' => $reply['model']],
            ]);

            $session->touch();

            return redirect()->route('chat.show', $session);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('chat.show', $session)->with('error', 'BiếtTuốt đang gặp sự cố. Vui lòng thử lại sau.');
        }
    }
}
