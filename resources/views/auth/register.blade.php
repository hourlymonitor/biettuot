@extends('layouts.app')
@section('title', 'Đăng ký')
@section('content')
<div class="card" style="max-width:460px;margin:36px auto"><h1>Tạo tài khoản</h1><p class="muted">Tham gia cộng đồng BiếtTuốt.</p>
<form method="POST" action="{{ route('register') }}">@csrf
<label for="name">Tên hiển thị</label><input class="input" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="100" required>
<label for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
<label for="password">Mật khẩu</label><input class="input" id="password" name="password" type="password" autocomplete="new-password" required>
<label for="password_confirmation">Nhập lại mật khẩu</label><input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
<button class="btn" type="submit">Đăng ký</button></form><p class="muted">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p></div>
@endsection
