@extends('layouts.app')
@section('title', $chatSession->title)
@section('content')
<div style="display:grid;grid-template-columns:minmax(220px,280px) 1fr;gap:18px">
<aside class="card"><h3>Cuộc trò chuyện</h3><a class="btn secondary" href="{{ route('chat.index') }}">＋ Cuộc trò chuyện mới</a>@foreach($sessions as $item)<p><a href="{{ route('chat.show', $item) }}">{{ Str::limit($item->title, 42) }}</a></p>@endforeach</aside>
<section class="card"><h1>{{ $chatSession->title }}</h1><div style="max-height:58vh;overflow:auto;margin:20px 0">@forelse($messages as $message)<div class="bubble {{ $message->role === 'user' ? 'user' : 'assistant' }}"><strong>{{ $message->role === 'user' ? 'Bạn' : 'BiếtTuốt' }}</strong><div>{{ $message->content }}</div></div>@empty<p class="muted">Chưa có tin nhắn.</p>@endforelse</div>
<form method="POST" action="{{ route('chat.send') }}">@csrf<input type="hidden" name="chat_session_id" value="{{ $chatSession->id }}"><div class="form-row"><textarea class="input" name="message" rows="2" maxlength="12000" placeholder="Nhập câu hỏi tiếp theo..." required></textarea><button class="btn" type="submit">Gửi</button></div></form></section></div>
@endsection
