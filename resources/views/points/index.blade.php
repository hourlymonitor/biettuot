@extends('layouts.app')
@section('title', 'Điểm thưởng')
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <h1>Điểm thưởng</h1>
    <p class="muted">Số dư hiện tại: <strong>{{ $balance }}</strong> điểm</p>

    <ul>
        @forelse($ledger as $entry)
            <li>{{ $entry->reason }} — {{ $entry->points }} điểm</li>
        @empty
            <li>Chưa có giao dịch nào.</li>
        @endforelse
    </ul>
</div>
@endsection
