@extends('layouts.app')
@section('title', 'Đổi quà')
@section('content')
<div class="card" style="max-width:980px;margin:auto"><h1>Đổi quà</h1><p class="muted">Số dư hiện tại: <strong>{{ number_format($balance) }}</strong> điểm</p>
<div class="stats">@forelse($rewards as $reward)<article class="card"><h2>{{ $reward->name }}</h2><p class="muted">{{ $reward->description }}</p><p><strong>{{ number_format($reward->cost_points) }} điểm</strong></p><form method="POST" action="{{ route('rewards.redeem', $reward) }}">@csrf<button class="btn" type="submit" @disabled($balance < $reward->cost_points)>Đổi quà</button></form></article>@empty<p>Chưa có quà tặng.</p>@endforelse</div>
<h2 style="margin-top:30px">Yêu cầu của tôi</h2><div class="tablewrap"><table><thead><tr><th>Quà</th><th>Ngày</th><th>Trạng thái</th><th>Ghi chú</th></tr></thead><tbody>@forelse($claims as $claim)<tr><td>{{ $claim->reward->name ?? 'Quà đã xóa' }}</td><td>{{ $claim->created_at->format('d/m/Y') }}</td><td>{{ $claim->status }}</td><td>{{ $claim->notes }}</td></tr>@empty<tr><td colspan="4">Chưa có yêu cầu đổi quà.</td></tr>@endforelse</tbody></table></div></div>
@endsection
