@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Danh sách thành viên nhóm</h1>
    <a href="{{ route('admin.groupmember.create', $groupId) }}" class="btn btn-primary">Tạo thành viên mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>role</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupMembers as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->role }}</td>
                    <td>{{ $member->status }}</td>
                    <td>
                        <a href="{{ route('admin.groupmember.edit', ['groupId' => $groupId, 'id' => $member->id]) }}">Chỉnh sửa</a>
                        <form action="{{ route('admin.groupmember.destroy', $member->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $groupMembers->links() }} <!-- Phân trang -->
</div>
@endsection