@extends('layouts.app')
@section('title', 'Cộng tác viên')
@section('content')
<div class="card" style="max-width:950px;margin:24px auto"><h1>Khu vực cộng tác viên</h1><p class="muted">Gửi hồ sơ để tham gia đóng góp nội dung cho cộng đồng.</p>
<div class="stats" style="margin:20px 0"><div class="card"><span class="muted">Trạng thái hồ sơ</span><h2>{{ $profile->status ?? 'Chưa đăng ký' }}</h2></div><div class="card"><span class="muted">Số dư điểm</span><h2>{{ number_format($points) }}</h2></div></div>
@if(!$profile || $profile->status !== 'active')<form method="POST" action="{{ route('contributors.apply') }}">@csrf<label for="skills">Kỹ năng / chuyên môn</label><textarea class="input" id="skills" name="skills" maxlength="2000" rows="4" required>{{ old('skills', $profile->skills ?? '') }}</textarea><label for="bio">Giới thiệu</label><textarea class="input" id="bio" name="bio" maxlength="2000" rows="3">{{ old('bio', $profile->bio ?? '') }}</textarea><button class="btn" type="submit">{{ $profile ? 'Cập nhật và gửi xét duyệt' : 'Đăng ký cộng tác viên' }}</button></form>@else<p class="success">Hồ sơ của bạn đã được duyệt. Cảm ơn bạn đã đóng góp!</p>@endif
<h2 style="margin-top:32px">Lịch sử điểm</h2><ul>@forelse($recent as $entry)<li>{{ $entry->reason }} — {{ $entry->points > 0 ? '+' : '' }}{{ $entry->points }}</li>@empty<li class="muted">Chưa có giao dịch.</li>@endforelse</ul></div>
@endsection
