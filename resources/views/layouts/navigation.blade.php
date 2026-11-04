<nav class="navlinks">
    @auth
        <a href="{{ route('chat.index') }}">Chat</a>
        <a href="{{ route('content.index') }}">Bài viết</a>
        <a href="{{ route('contributors.dashboard') }}">Cộng tác viên</a>
        <a href="{{ route('points.index') }}">Điểm</a>
        <a href="{{ route('rewards.index') }}">Đổi quà</a>
        @php($staff = collect(array_merge(config('admin.emails', []), config('admin.moderator_emails', [])))->map(fn($email) => mb_strtolower($email))->contains(mb_strtolower(auth()->user()->email)))
        @if($staff)<a href="{{ route('moderation.index') }}">Duyệt bài</a><a href="{{ route('admin.analytics') }}">Báo cáo</a>@endif
        @if(collect(config('admin.emails', []))->map(fn($email) => mb_strtolower($email))->contains(mb_strtolower(auth()->user()->email)))<a href="{{ route('admin.dashboard') }}">Quản trị</a>@endif
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn secondary" type="submit">Đăng xuất</button></form>
    @else
        <a href="{{ route('login') }}">Đăng nhập</a><a class="btn" href="{{ route('register') }}">Tạo tài khoản</a>
    @endauth
</nav>
