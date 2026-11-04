<?php

namespace App\Http\Controllers;

use App\Models\PointLedger;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class RewardController extends Controller
{
    public function index(): View
    {
        $rewards = Reward::query()->where('active', true)->orderBy('cost_points')->get();
        $claims = RewardClaim::with('reward')->where('user_id', auth()->id())->latest()->limit(20)->get();
        $balance = PointLedger::where('user_id', auth()->id())->sum('points');

        return view('rewards.index', compact('rewards', 'claims', 'balance'));
    }

    public function redeem(Request $request, Reward $reward): RedirectResponse
    {
        try {
            DB::transaction(function () use ($reward): void {
                // Lock the account row so two simultaneous redemptions cannot spend the same balance.
                $user = \App\Models\User::query()->whereKey(auth()->id())->lockForUpdate()->firstOrFail();
                $lockedReward = Reward::query()->whereKey($reward->id)->lockForUpdate()->firstOrFail();
                abort_unless($lockedReward->active && $lockedReward->cost_points > 0, 404);

                $balance = (int) $user->pointLedger()->sum('points');
                if ($balance < $lockedReward->cost_points) {
                    throw new RuntimeException('insufficient_points');
                }

                RewardClaim::create([
                    'user_id' => $user->id,
                    'reward_id' => $lockedReward->id,
                    'status' => 'pending',
                    'notes' => null,
                ]);
                $user->pointLedger()->create([
                    'points' => -$lockedReward->cost_points,
                    'reason' => 'Redeem reward: '.$lockedReward->name,
                    'source' => 'reward_redeem',
                ]);
            });
        } catch (RuntimeException $exception) {
            if ($exception->getMessage() === 'insufficient_points') {
                return back()->with('error', 'Bạn không đủ điểm để đổi quà này.');
            }
            throw $exception;
        }

        return redirect()->route('rewards.index')->with('status', 'Yêu cầu đổi quà đã được ghi nhận.');
    }
}
