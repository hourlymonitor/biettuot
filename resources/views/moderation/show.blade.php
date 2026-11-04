@extends('layouts.app')
@section('title', 'Chi tiết bài viết')
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <h1>{{ $contentPost->title }}</h1>
    <p class="muted">Tác giả: {{ $contentPost->user->name }}</p>

    <div style="white-space:pre-wrap;line-height:1.8;margin:24px 0">{{ $contentPost->body }}</div>

    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
        <form method="POST" action="{{ route('moderation.approve', $contentPost) }}">@csrf
            <textarea class="input" name="notes" rows="3" placeholder="Ghi chú duyệt (tùy chọn)"></textarea>
            <button class="btn" type="submit">Duyệt</button>
        </form>

        <form method="POST" action="{{ route('moderation.reject', $contentPost) }}">@csrf
            <textarea class="input" name="notes" rows="3" placeholder="Lý do từ chối" required></textarea>
            <button class="btn" type="submit" style="background:#d92d20;border-color:#d92d20">Từ chối</button>
        </form>
    </div>

    @if($reviews->isNotEmpty())
        <h3 style="margin-top:32px">Lịch sử review</h3>
        <ul>
            @foreach($reviews as $review)
                <li><strong>{{ $review->status }}</strong> — {{ $review->notes ?? 'Không có ghi chú' }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
