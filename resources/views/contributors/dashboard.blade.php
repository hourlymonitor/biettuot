@extends('layouts.app')
@section('title', 'Cộng tác viên')
@section('content')
<div class="card" style="max-width:980px;margin:32px auto">
    <h1>Cộng tác viên</h1>
    <p class="muted">Kết nối với cộng đồng, đóng góp nội dung và nhận điểm.</p>

    <div class="card" style="padding:18px;border:1px solid #e7e9f0;margin-bottom:18px">
        <div><strong>Hiện tại:</strong> {{ $profile->status ?? 'Chưa đăng ký' }}</div>
        <div><strong>Điểm hiện có:</strong> {{ $points }}</div>
    </div>

    <form method="POST" action="{{ route('contributors.apply') }}">@csrf
        <label for="skills">Kỹ năng / chuyên môn</label>
        <textarea class="input" id="skills" name="skills" rows="4" required>{{ old('skills', $profile->skills ?? '') }}</textarea>
        <label for="bio">Giới thiệu ngắn</label>
        <textarea class="input" id="bio" name="bio" rows="4">{{ old('bio', $profile->bio ?? '') }}</textarea>
        <button class="btn" type="submit">Gửi đơn</button>
    </form>

    <h3 style="margin-top:28px">Lịch sử điểm</h3>
    <ul>
        @forelse($recent as $entry)
            <li>{{ $entry->reason }} — {{ $entry->points }} điểm</li>
        @empty
            <li>Chưa có điểm nào.</li>
        @endforelse
    </ul>
</div>
@endsection
