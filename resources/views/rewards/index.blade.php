@extends('layouts.app')
@section('title', 'Đổi quà')
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <h1>Đổi quà</h1>

    @forelse($rewards as $reward)
        <div class="card" style="padding:18px;border:1px solid #e7e9f0;margin-bottom:14px">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
                <div>
                    <h3 style="margin:0">{{ $reward->name }}</h3>
                    <p class="muted" style="margin:8px 0 0">{{ $reward->description }}</p>
                </div>
                <form method="POST" action="{{ route('rewards.redeem', $reward) }}">@csrf
                    <button class="btn" type="submit">Đổi {{ $reward->cost_points }} điểm</button>
                </form>
            </div>
        </div>
    @empty
        <p class="muted">Hiện chưa có phần thưởng nào.</p>
    @endforelse

    @if($claims->isNotEmpty())
        <h3>Lịch sử yêu cầu</h3>
        <ul>
            @foreach($claims as $claim)
                <li>{{ $claim->reward->name ?? 'Quà' }} — {{ $claim->status }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
