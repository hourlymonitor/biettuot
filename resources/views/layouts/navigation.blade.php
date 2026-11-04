<nav style="display:flex;gap:14px;align-items:center;flex-wrap:wrap">
    @auth
        <a href="{{ route('chat.index') }}">Chat</a>
        <a href="{{ route('content.index') }}">Nội dung</a>
        <a href="{{ route('contributors.dashboard') }}">Cộng tác viên</a>
        <a href="{{ route('points.index') }}">Điểm</a>
        <a href="{{ route('rewards.index') }}">Đổi quà</a>
        @if(collect(array_merge(config('admin.emails', []), config('admin.moderator_emails', [])))->map(fn($email) => mb_strtolower($email))->contains(mb_strtolower(auth()->user()->email)))
            <a href="{{ route('moderation.index') }}">Duyệt bài</a>
            <a href="{{ route('admin.analytics') }}">Báo cáo</a>
        @endif
        @if(collect(config('admin.emails', []))->map(fn($email) => mb_strtolower($email))->contains(mb_strtolower(auth()->user()->email)))
            <a href="{{ route('admin.dashboard') }}">Quản trị</a>
        @endif
    @endauth
</nav>
