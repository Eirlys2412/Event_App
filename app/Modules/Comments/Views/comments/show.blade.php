@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thông Tin Người Dùng</h2>

    <div class="mt-5">
        <h3>Tên: {{ $user->full_name }}</h3>
        <h3>Email: {{ $user->email }}</h3>
        <h3>Ngày tạo: {{ $user->created_at }}</h3>
        <!-- Thêm thông tin người dùng khác nếu cần -->
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Trở lại danh sách người dùng</a>
@endsection
