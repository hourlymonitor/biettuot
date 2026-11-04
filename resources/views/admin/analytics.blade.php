@extends('layouts.app')
@section('title', 'Analytics & Reports')
@section('content')
<div class="card" style="max-width:1180px;margin:32px auto">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap">
        <div>
            <h1>Analytics &amp; Reports</h1>
            <p class="muted">Thống kê AI, nội dung và hoạt động kiểm duyệt theo khoảng thời gian.</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a class="btn secondary" href="{{ route('admin.reports.ai', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}">Tải CSV AI</a>
            <a class="btn secondary" href="{{ route('admin.reports.moderation', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}">Tải CSV moderation</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.analytics') }}" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap;margin:20px 0">
        <div><label for="from">Từ ngày</label><input class="input" style="margin-bottom:0" id="from" name="from" type="date" value="{{ $from->toDateString() }}"></div>
        <div><label for="to">Đến ngày</label><input class="input" style="margin-bottom:0" id="to" name="to" type="date" value="{{ $to->toDateString() }}"></div>
        <button class="btn" type="submit">Lọc báo cáo</button>
    </form>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin:20px 0">
        @foreach([
            'Người dùng mới' => $stats['new_users'],
            'Cuộc trò chuyện' => $stats['conversations'],
            'Bài viết tạo mới' => $stats['content_posts'],
            'Bài đang chờ duyệt' => $stats['pending_posts'],
            'AI calls' => $stats['ai_calls'],
            'AI lỗi' => $stats['ai_failures'],
            'Thời gian AI TB' => $stats['ai_avg_duration_ms'].' ms',
            'Token prompt' => number_format($stats['prompt_tokens']),
            'Token hoàn tất' => number_format($stats['completion_tokens']),
        ] as $label => $value)
            <div class="card" style="padding:16px;background:#f8f9ff;border:1px solid #e7e9f0"><span class="muted">{{ $label }}</span><div style="font-size:25px;font-weight:800;margin-top:8px">{{ $value }}</div></div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-top:20px">
        <section class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>Người dùng mới theo ngày</h3>
            @forelse($dailyUsers as $day => $total)
                <div style="display:grid;grid-template-columns:96px 1fr 36px;gap:8px;align-items:center;margin:10px 0"><small>{{ $day }}</small><div style="height:12px;background:#eef0ff;border-radius:10px"><div style="height:12px;width:{{ min(100, max(2, (int) round($total / $maxDaily * 100))) }}%;background:#4f46e5;border-radius:10px"></div></div><strong>{{ $total }}</strong></div>
            @empty<p class="muted">Không có dữ liệu trong khoảng này.</p>@endforelse
        </section>
        <section class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>AI calls theo ngày</h3>
            @forelse($dailyAiCalls as $day => $total)
                <div style="display:grid;grid-template-columns:96px 1fr 36px;gap:8px;align-items:center;margin:10px 0"><small>{{ $day }}</small><div style="height:12px;background:#eef0ff;border-radius:10px"><div style="height:12px;width:{{ min(100, max(2, (int) round($total / $maxDaily * 100))) }}%;background:#0e9384;border-radius:10px"></div></div><strong>{{ $total }}</strong></div>
            @empty<p class="muted">Không có dữ liệu trong khoảng này.</p>@endforelse
        </section>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-top:16px">
        <section class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>Hiệu suất AI theo provider/model</h3>
            <div style="overflow-x:auto"><table style="width:100%;text-align:left;border-collapse:collapse"><thead><tr><th>Provider / model</th><th>Calls</th><th>Lỗi</th><th>TB ms</th></tr></thead><tbody>
                @forelse($aiByProvider as $row)
                    <tr><td>{{ $row->provider }} / {{ $row->model }}</td><td>{{ $row->calls }}</td><td>{{ $row->failures }}</td><td>{{ (int) round($row->avg_duration_ms ?? 0) }}</td></tr>
                @empty<tr><td colspan="4" class="muted">Chưa có dữ liệu.</td></tr>@endforelse
            </tbody></table></div>
        </section>
        <section class="card" style="padding:18px;border:1px solid #e7e9f0">
            <h3>Kết quả moderation</h3>
            @forelse($reviewStatuses as $status => $total)
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee"><span>{{ $status }}</span><strong>{{ $total }}</strong></div>
            @empty<p class="muted">Chưa có lượt review trong khoảng này.</p>@endforelse
        </section>
    </div>

    <section class="card" style="padding:18px;border:1px solid #e7e9f0;margin-top:16px">
        <h3>Hoạt động moderator</h3>
        <div style="overflow-x:auto"><table style="width:100%;text-align:left;border-collapse:collapse"><thead><tr><th>Tên</th><th>Email</th><th>Số lượt review</th></tr></thead><tbody>
            @forelse($topReviewers as $reviewer)
                <tr><td>{{ $reviewer->name }}</td><td>{{ $reviewer->email }}</td><td>{{ $reviewer->reviews }}</td></tr>
            @empty<tr><td colspan="3" class="muted">Chưa có hoạt động moderation trong khoảng này.</td></tr>@endforelse
        </tbody></table></div>
    </section>
</div>
@endsection
