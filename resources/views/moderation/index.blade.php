@extends('layouts.app')
@section('title', 'Duyệt nội dung')
@section('content')
<div class="card" style="max-width:1100px;margin:32px auto">
    <h1>Queue duyệt nội dung</h1>

    @if($queue->isEmpty())
        <p class="muted">Hiện không có bài viết nào đang chờ duyệt.</p>
    @else
        <div style="display:grid;gap:14px">
            @foreach($queue as $post)
                <div class="card" style="padding:18px;border:1px solid #e7e9f0;border-radius:12px">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                        <div>
                            <h3 style="margin:0"><a href="{{ route('moderation.show', $post) }}">{{ $post->title }}</a></h3>
                            <p class="muted" style="margin:8px 0 0">Tác giả: {{ $post->user->name ?? 'Không rõ' }}</p>
                        </div>
                        <span class="muted">{{ $post->status }}</span>
                    </div>
                    <p style="margin-top:12px">{{ Str::limit(strip_tags($post->body), 220) }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
