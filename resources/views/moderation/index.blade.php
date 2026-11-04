@extends('layouts.app')
@section('title', 'Duyệt nội dung')
@section('content')
<div class="card"><h1>Hàng chờ duyệt</h1><p class="muted">Bài viết được duyệt sẽ cộng 50 điểm một lần cho tác giả.</p>
<div class="tablewrap"><table><thead><tr><th>Bài viết</th><th>Tác giả</th><th>Cập nhật</th><th></th></tr></thead><tbody>
@forelse($queue as $post)<tr><td><strong>{{ $post->title }}</strong><br><span class="muted">{{ Str::limit(strip_tags($post->body), 180) }}</span></td><td>{{ $post->user->name ?? '—' }}</td><td>{{ $post->updated_at->format('d/m/Y H:i') }}</td><td><a class="btn secondary" href="{{ route('moderation.show', $post) }}">Mở duyệt</a></td></tr>@empty<tr><td colspan="4">Không có bài viết đang chờ.</td></tr>@endforelse
</tbody></table></div>{{ $queue->links() }}</div>
@endsection
