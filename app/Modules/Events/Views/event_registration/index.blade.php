@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách đăng ký sự kiện</h2>

    <a href="{{ route('admin.event_registration.create') }}" class="btn btn-primary mb-3">Tạo đăng ký mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sự kiện</th>
                <th>Người dùng</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registrations as $registration)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $registration->event->title ?? '-' }}</td>
                    <td>{{ $registration->user->full_name ?? '-' }}</td>
                    <td>{{ $registration->role->title ?? '-' }}</td>
                    <td>{{ ucfirst($registration->status) }}</td>
                    <td>
                        <a href="{{ route('admin.event_registration.edit', $registration->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.event_registration.destroy', $registration->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $registrations->links() }}
</div>
@endsection
