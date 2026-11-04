@extends('layouts.app')
@section('title', 'Trợ lý AI tiếng Việt')
@section('content')
<section class="card" style="max-width:820px;margin:8vh auto;text-align:center;padding:clamp(28px,6vw,64px)">
    <div style="font-size:42px">✦</div><p class="muted" style="font-weight:700">TRỢ LÝ AI &amp; CỘNG ĐỒNG TRI THỨC</p>
    <h1 style="font-size:clamp(34px,7vw,58px);line-height:1.1;margin:14px 0">Học hỏi và sáng tạo cùng BiếtTuốt.</h1>
    <p class="muted" style="font-size:18px;line-height:1.7;max-width:650px;margin:0 auto 24px">Trò chuyện với AI, tạo bản nháp nội dung, đóng góp cho cộng đồng và tích lũy điểm thưởng.</p>
    <a class="btn" href="{{ route('register') }}">Bắt đầu miễn phí</a> <a class="btn secondary" href="{{ route('login') }}">Đăng nhập</a>
    <p class="muted" style="margin-top:24px"><small>AI có thể trả lời chưa chính xác. Hãy kiểm chứng thông tin quan trọng.</small></p>
</section>
@endsection
