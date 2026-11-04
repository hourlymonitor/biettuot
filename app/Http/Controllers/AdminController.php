<?php

namespace App\Http\Controllers;

use App\Models\AiUsageLog;
use App\Models\ChatSession;
use App\Models\ContentPost;
use App\Models\ContributorProfile;
use App\Models\RewardClaim;
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
            'pending_posts' => ContentPost::where('status', 'draft')->count(),
            'pending_contributors' => ContributorProfile::where('status', 'pending')->count(),
            'pending_rewards' => RewardClaim::where('status', 'pending')->count(),
            'ai_calls' => AiUsageLog::count(),
            'ai_failures' => AiUsageLog::where('status', 'failed')->count(),
        ];

        $recentLogs = AiUsageLog::latest()->limit(8)->get();
        $recentPosts = ContentPost::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentLogs', 'recentPosts'));
    }
}
