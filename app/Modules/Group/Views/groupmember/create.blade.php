@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Thêm Thành Viên vào Nhóm</h1>
    <form method="POST" action="{{ route('admin.groupmember.store', $groupId) }}">
        @csrf
        <div class="form-group">
            <label for="user_id">Chọn người dùng</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Chọn người dùng</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->full_name }}</option> <!-- Sử dụng full_name để hiển thị -->
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="role">Vai trò</label>
            <select name="role" class="form-control" required>
                <option value="member">Thành viên</option>
                <option value="admin">Quản trị viên</option>
                <option value="lecturer">Giảng viên</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control" required>
                <option value="active">Hoạt động</option>
                <option value="inactive">Không hoạt động</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.groupmember.index', $groupId) }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection