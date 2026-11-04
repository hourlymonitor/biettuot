@extends('layouts.app')
@section('title', 'Tạo bài viết')
@section('content')
<div class="card" style="max-width:900px;margin:32px auto">
    <h1>Tạo bài viết bằng AI</h1>
    <p class="muted">Tạo bản nháp, xem lại và chỉnh sửa trước khi sử dụng.</p>

    <form method="POST" action="{{ route('content.generate') }}">@csrf
        <label for="prompt">Chủ đề / câu hỏi</label>
        <textarea class="input" name="prompt" id="prompt" rows="4" maxlength="2000" placeholder="Ví dụ: Hướng dẫn viết CV cho sinh viên mới ra trường" required>{{ old('prompt', session('prompt')) }}</textarea>
        <button class="btn" type="submit">Tạo bản nháp</button>
    </form>

    @if(session('draft'))
        <div style="margin-top:28px">
            <h3>Bản nháp AI — cần kiểm tra trước khi đăng</h3>
            <form method="POST" action="{{ route('content.store') }}">@csrf
                <input type="hidden" name="source_prompt" value="{{ session('prompt') }}">
                <label for="title">Tiêu đề</label>
                <input class="input" id="title" name="title" value="{{ old('title', Str::limit(trim(explode("\n", session('draft'))[0]), 120)) }}" maxlength="200" required>
                <label for="body">Nội dung</label>
                <textarea class="input" id="body" name="body" rows="16" maxlength="20000" required>{{ old('body', session('draft')) }}</textarea>
                <button class="btn" type="submit">Lưu bản nháp</button>
            </form>
        </div>
    @endif
</div>
@endsection
