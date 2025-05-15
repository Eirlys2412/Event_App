@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Chỉnh sửa thành viên nhóm</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.groupmember.update', ['groupId' => $groupId, 'id' => $groupMember->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="full_name">Tên</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $groupMember->full_name }}" required>
        </div>

        <div class="form-group">
            <label for="role">Vai trò</label>
            <select class="form-control" id="role" name="role">
                <option value="admin" {{ $groupMember->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="member" {{ $groupMember->role == 'member' ? 'selected' : '' }}>Member</option>
                <!-- Thêm các vai trò khác nếu cần -->
            </select>
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select class="form-control" id="status" name="status">
                <option value="active" {{ $groupMember->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ $groupMember->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.groupmember.index', $groupId) }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection