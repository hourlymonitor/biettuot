@extends('layouts.app')
@section('title', 'Quản lý cộng tác viên')
@section('content')
<div class="card"><h1>Quản lý cộng tác viên</h1><div class="tablewrap"><table><thead><tr><th>Tên</th><th>Email</th><th>Kỹ năng</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
@forelse($profiles as $profile)<tr><td>{{ $profile->display_name }}</td><td>{{ $profile->user->email }}</td><td>{{ $profile->skills }}</td><td>{{ $profile->status }}</td><td><form method="POST" action="{{ route('admin.contributors.update', $profile) }}">@csrf @method('PATCH')<select class="input" name="status"><option value="pending" @selected($profile->status === 'pending')>Chờ duyệt</option><option value="active" @selected($profile->status === 'active')>Đã duyệt</option><option value="rejected" @selected($profile->status === 'rejected')>Từ chối</option></select><button class="btn" type="submit">Lưu</button></form></td></tr>@empty<tr><td colspan="5">Chưa có hồ sơ cộng tác viên.</td></tr>@endforelse
</tbody></table></div>{{ $profiles->links() }}</div>
@endsection
