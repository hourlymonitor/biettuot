@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('content')
<div class="card" style="max-width:460px;margin:36px auto"><h1>Đăng nhập</h1><p class="muted">Tiếp tục sử dụng BiếtTuốt.</p>
<form method="POST" action="{{ route('login') }}">@csrf
<label for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
<label for="password">Mật khẩu</label><input class="input" id="password" name="password" type="password" autocomplete="current-password" required>
<p><label><input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập</label></p><button class="btn" type="submit">Đăng nhập</button>
</form><p class="muted">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p></div>
@endsection
