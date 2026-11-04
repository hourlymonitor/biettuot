@extends('layouts.app')
@section('title', $contentPost->title)
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <h1>{{ $contentPost->title }}</h1>
    <p class="muted">Trạng thái: {{ $contentPost->status }}</p>
    <div style="white-space:pre-wrap;line-height:1.8;margin-top:24px">{{ $contentPost->body }}</div>
    @if($contentPost->source_prompt)
        <p class="muted" style="margin-top:20px"><small>Chủ đề gốc: {{ $contentPost->source_prompt }}</small></p>
    @endif
</div>
@endsection
