@extends('layouts.app')
@section('title', 'Trò chuyện')
@section('content')
<div style="display:grid;grid-template-columns:minmax(220px,280px) 1fr;gap:18px">
<aside class="card"><h3>Cuộc trò chuyện</h3><a class="btn secondary" href="{{ route('chat.index') }}">＋ Cuộc trò chuyện mới</a>@foreach($sessions as $item)<p><a href="{{ route('chat.show', $item) }}">{{ Str::limit($item->title, 42) }}</a></p>@endforeach</aside>
<section class="card"><h1>Hỏi BiếtTuốt</h1><p class="muted">Bạn muốn tìm hiểu điều gì hôm nay?</p>
<form method="POST" action="{{ route('chat.send') }}">@csrf<label for="message">Câu hỏi</label><textarea class="input" id="message" name="message" rows="5" maxlength="12000" placeholder="Viết câu hỏi của bạn..." required></textarea><button class="btn" type="submit">Gửi câu hỏi</button></form>
<p class="muted"><small>Không gửi mật khẩu hoặc dữ liệu nhạy cảm. Câu trả lời AI có thể cần kiểm chứng.</small></p></section></div>
@endsection
