<?php

namespace App\Http\Controllers;

use App\Models\AiUsageLog;
use App\Models\ChatSession;
use App\Models\ContentPost;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users' => User::count(),
            'conversations' => ChatSession::count(),
            'posts' => ContentPost::count(),
            'ai_calls' => AiUsageLog::count(),
        ];

        $recentLogs = AiUsageLog::query()
            ->latest('created_at')
            ->limit(10)
            ->get();

        $recentPosts = ContentPost::query()
            ->latest('created_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLogs', 'recentPosts'));
    }
}
