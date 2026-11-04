@extends('layouts.app')
@section('title', 'Dashboard quản trị')
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><div><h1>Dashboard quản trị</h1><p class="muted">Tổng quan hoạt động BiếtTuốt.</p></div><a class="btn secondary" href="{{ route('admin.analytics') }}">Analytics &amp; báo cáo</a></div>
    <div class="stats" style="margin:20px 0">
        @foreach(['Người dùng'=>'users','Cuộc trò chuyện'=>'conversations','Tất cả bài viết'=>'posts','Chờ duyệt'=>'pending_posts','Đơn cộng tác viên'=>'pending_contributors','Yêu cầu đổi quà'=>'pending_rewards','AI calls'=>'ai_calls','AI lỗi'=>'ai_failures'] as $label=>$key)
            <div class="card"><span class="muted">{{ $label }}</span><div style="font-size:28px;font-weight:800;margin-top:8px">{{ number_format($stats[$key]) }}</div></div>
        @endforeach
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin:20px 0"><a class="btn" href="{{ route('moderation.index') }}">Duyệt nội dung</a><a class="btn secondary" href="{{ route('admin.contributors') }}">Quản lý cộng tác viên</a><a class="btn secondary" href="{{ route('admin.rewards') }}">Quản lý quà &amp; yêu cầu</a></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px">
        <section class="card"><h2>Bài viết mới</h2><div class="tablewrap"><table><thead><tr><th>Bài viết</th><th>Tác giả</th><th>Trạng thái</th></tr></thead><tbody>@forelse($recentPosts as $post)<tr><td><a href="{{ route('moderation.show', $post) }}">{{ $post->title }}</a></td><td>{{ $post->user->name ?? '—' }}</td><td>{{ $post->status }}</td></tr>@empty<tr><td colspan="3">Chưa có nội dung.</td></tr>@endforelse</tbody></table></div></section>
        <section class="card"><h2>AI calls gần đây</h2><div class="tablewrap"><table><thead><tr><th>Provider</th><th>Model</th><th>Kết quả</th><th>Thời gian</th></tr></thead><tbody>@forelse($recentLogs as $log)<tr><td>{{ $log->provider }}</td><td>{{ $log->model }}</td><td>{{ $log->status }}</td><td>{{ $log->duration_ms }} ms</td></tr>@empty<tr><td colspan="4">Chưa có AI call.</td></tr>@endforelse</tbody></table></div></section>
    </div>
</div>
@endsection
