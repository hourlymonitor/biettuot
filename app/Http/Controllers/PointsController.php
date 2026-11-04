<?php

namespace App\Http\Controllers;

use App\Models\PointLedger;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointsController extends Controller
{
    public function index(): View
    {
        $balance = PointLedger::where('user_id', auth()->id())->sum('points');
        $ledger = PointLedger::where('user_id', auth()->id())->latest()->limit(20)->get();

        return view('points.index', compact('balance', 'ledger'));
    }

    public function award(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'points' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        PointLedger::create([
            'user_id' => $validated['user_id'],
            'points' => $validated['points'],
            'reason' => $validated['reason'],
            'source' => 'manual_admin',
        ]);

        return back()->with('status', 'Điểm đã được cộng thành công.');
    }
}
