@extends('layouts.app')
@section('title', 'Bài viết của tôi')
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap">
        <h1>Bài viết của tôi</h1>
        <a class="btn" href="{{ route('content.create') }}">Tạo bài mới</a>
    </div>

    @forelse($posts as $post)
        <div class="card" style="margin-top:14px;padding:18px;border:1px solid #e7e9f0;border-radius:12px">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap">
                <h3 style="margin:0"><a href="{{ route('content.show', $post) }}">{{ $post->title }}</a></h3>
                <span class="muted">{{ $post->status }}</span>
            </div>
            <p class="muted" style="margin:8px 0 0">{{ Str::limit(strip_tags($post->body), 180) }}</p>
        </div>
    @empty
        <p class="muted">Bạn chưa có bài viết nào. Hãy tạo một bài mới bằng AI.</p>
    @endforelse

    <div style="margin-top:20px">{{ $posts->links() }}</div>
</div>
@endsection
