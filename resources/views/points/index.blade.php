@extends('layouts.app')
@section('title', 'Điểm thưởng')
@section('content')
<div class="card" style="max-width:980px;margin:auto"><h1>Điểm thưởng</h1><p class="muted">Số dư: <strong>{{ number_format($balance) }}</strong> điểm</p><div class="tablewrap"><table><thead><tr><th>Thời gian</th><th>Lý do</th><th>Điểm</th></tr></thead><tbody>@forelse($ledger as $entry)<tr><td>{{ $entry->created_at->format('d/m/Y H:i') }}</td><td>{{ $entry->reason }}</td><td>{{ $entry->points > 0 ? '+' : '' }}{{ $entry->points }}</td></tr>@empty<tr><td colspan="3">Chưa có giao dịch.</td></tr>@endforelse</tbody></table></div>{{ $ledger->links() }}</div>
@endsection
