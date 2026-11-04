<?php

namespace App\Http\Controllers;

use App\Models\ContributorProfile;
use App\Models\PointLedger;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContributorController extends Controller
{
    public function dashboard(): View
    {
        $profile = auth()->user()->contributorProfile()->firstOrCreate([
            'user_id' => auth()->id(),
            'display_name' => auth()->user()->name,
            'status' => 'active',
        ]);

        $points = PointLedger::where('user_id', auth()->id())->sum('points');
        $recent = PointLedger::where('user_id', auth()->id())->latest()->limit(10)->get();

        return view('contributors.dashboard', compact('profile', 'points', 'recent'));
    }

    public function apply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skills' => ['required', 'string', 'max:2000'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        auth()->user()->contributorProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'display_name' => auth()->user()->name,
                'skills' => $validated['skills'],
                'bio' => $validated['bio'] ?? null,
                'status' => 'pending',
            ]
        );

        return redirect()->route('contributors.dashboard')->with('status', 'Đơn tham gia cộng tác viên đã được gửi.');
    }
}
