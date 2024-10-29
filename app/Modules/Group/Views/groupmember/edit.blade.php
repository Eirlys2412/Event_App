@extends('backend.layouts.master')

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa thành viên</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="POST" action="{{ route('admin.groupmember.update', ['groupId' => $groupId, 'id' => $groupMember->id]) }}">
            @csrf
            @method('PUT')
        
            <div class="form-group">
                <label for="user_id">Chọn người dùng</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Chọn người dùng</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $groupMember->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        
            <div class="form-group">
                <label for="role">Vai trò</label>
                <select name="role" class="form-control" required>
                    <option value="member" {{ $groupMember->role == 'member' ? 'selected' : '' }}>Thành viên</option>
                    <option value="admin" {{ $groupMember->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    <option value="lecturer" {{ $groupMember->role == 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                </select>
            </div>
        
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ $groupMember->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ $groupMember->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
        
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.groupmember.index', $groupId) }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

@endsection