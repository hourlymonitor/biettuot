<?php

namespace App\Http\Controllers;

use App\Models\ContributorProfile;
use App\Models\PointLedger;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminOperationsController extends Controller
{
    public function contributors(): View
    {
        $profiles = ContributorProfile::with('user')->latest()->paginate(25);
        return view('admin.contributors', compact('profiles'));
    }

    public function updateContributor(Request $request, ContributorProfile $profile): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:active,rejected,pending']]);
        $profile->update(['status' => $data['status']]);
        return back()->with('status', 'Đã cập nhật trạng thái cộng tác viên.');
    }

    public function rewards(): View
    {
        $rewards = Reward::orderBy('cost_points')->paginate(25);
        $claims = RewardClaim::with(['user', 'reward'])->latest()->paginate(25, ['*'], 'claims_page');
        return view('admin.rewards', compact('rewards', 'claims'));
    }

    public function storeReward(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost_points' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);
        Reward::create($data + ['active' => $request->boolean('active')]);
        return back()->with('status', 'Đã tạo phần thưởng.');
    }

    public function updateReward(Request $request, Reward $reward): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost_points' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);
        $reward->update($data + ['active' => $request->boolean('active')]);
        return back()->with('status', 'Đã cập nhật phần thưởng.');
    }

    public function processClaim(Request $request, RewardClaim $claim): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:fulfilled,cancelled'], 'notes' => ['nullable', 'string', 'max:2000']]);

        DB::transaction(function () use ($claim, $data): void {
            $locked = RewardClaim::whereKey($claim->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== 'pending') {
                abort(409, 'Yêu cầu này đã được xử lý.');
            }
            $locked->update(['status' => $data['status'], 'notes' => $data['notes'] ?? null]);
            if ($data['status'] === 'cancelled') {
                $locked->user->pointLedger()->create([
                    'points' => (int) $locked->reward->cost_points,
                    'reason' => 'Refund cancelled reward: '.$locked->reward->name,
                    'source' => 'reward_refund',
                ]);
            }
        });

        return back()->with('status', 'Đã cập nhật yêu cầu đổi quà.');
    }
}
