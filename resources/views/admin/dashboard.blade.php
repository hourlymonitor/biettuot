@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="card" style="max-width:1100px;margin:32px auto">
    <h1>Dashboard Admin</h1>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin:24px 0">
        <div class="card" style="padding:18px;background:#f8f9ff;border:1px solid #e7e9f0"><strong>Người dùng</strong><div style="font-size:28px;font-weight:800;margin-top:10px">{{ $stats['users'] }}</div></div>
        <div class="card" style="padding:18px;background:#f8f9ff;border:1px solid #e7e9f0"><strong>Cuộc trò chuyện</strong><div style="font-size:28px;font-weight:800;margin-top:10px">{{ $stats['conversations'] }}</div></div>
        <div class="card" style="padding:18px;background:#f8f9ff;border:1px solid #e7e9f0"><strong>Bài viết</strong><div style="font-size:28px;font-weight:800;margin-top:10px">{{ $stats['posts'] }}</div></div>
        <div class="card" style="padding:18px;background:#f8f9ff;border:1px solid #e7e9f0"><strong>AI calls</strong><div style="font-size:28px;font-weight:800;margin-top:10px">{{ $stats['ai_calls'] }}</div></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
        <div class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>AI usage log gần đây</h3>
            <ul>
                @forelse($recentLogs as $log)
                    <li style="margin-bottom:8px">
                        {{ $log->provider }} / {{ $log->model }} — {{ $log->status }} — {{ $log->duration_ms }}ms
                    </li>
                @empty
                    <li>Chưa có log AI nào.</li>
                @endforelse
            </ul>
        </div>

        <div class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>Bài viết gần đây</h3>
            <ul>
                @forelse($recentPosts as $post)
                    <li style="margin-bottom:8px"><a href="{{ route('content.show', $post) }}">{{ $post->title }}</a> ({{ $post->status }})</li>
                @empty
                    <li>Chưa có bài viết nào.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
