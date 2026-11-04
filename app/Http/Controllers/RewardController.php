<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(): View
    {
        $rewards = Reward::query()->where('active', true)->get();
        $claims = RewardClaim::where('user_id', auth()->id())->latest()->limit(10)->get();

        return view('rewards.index', compact('rewards', 'claims'));
    }

    public function redeem(Request $request, Reward $reward): RedirectResponse
    {
        $balance = auth()->user()->pointBalance();

        if ($balance < $reward->cost_points) {
            return back()->with('error', 'Bạn không đủ điểm để đổi quà này.');
        }

        RewardClaim::create([
            'user_id' => auth()->id(),
            'reward_id' => $reward->id,
            'status' => 'pending',
            'notes' => 'Claim created by user',
        ]);

        auth()->user()->pointLedger()->create([
            'points' => -$reward->cost_points,
            'reason' => 'Redeem reward: '.$reward->name,
            'source' => 'reward_redeem',
        ]);

        return redirect()->route('rewards.index')->with('status', 'Yêu cầu đổi quà đã được ghi nhận.');
    }
}
