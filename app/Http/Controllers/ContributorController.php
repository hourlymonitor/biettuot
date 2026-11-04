<?php

namespace App\Http\Controllers;

use App\Models\ContributorProfile;
use App\Models\PointLedger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContributorController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $profile = $user->contributorProfile()->first();
        $points = (int) $user->pointLedger()->sum('points');
        $recent = $user->pointLedger()->latest()->limit(10)->get();

        return view('contributors.dashboard', compact('profile', 'points', 'recent'));
    }

    public function apply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skills' => ['required', 'string', 'max:2000'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        $profile = auth()->user()->contributorProfile;
        if ($profile?->status === 'active') {
            return back()->with('error', 'Hồ sơ cộng tác viên của bạn đã được duyệt.');
        }

        auth()->user()->contributorProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'display_name' => auth()->user()->name,
                'skills' => $validated['skills'],
                'bio' => $validated['bio'] ?? null,
                'status' => 'pending',
            ]
        );

        return redirect()->route('contributors.dashboard')->with('status', 'Đơn tham gia cộng tác viên đã được gửi để xét duyệt.');
    }
}
