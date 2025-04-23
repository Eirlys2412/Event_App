@extends('backend.layouts.master')

@section('content')
<div class="container py-4">
    <h2>Đăng ký tham gia sự kiện</h2>

    <form method="POST" action="{{ route('admin.event_registration.store') }}">
        @csrf

        <div class="mb-3">
            <label for="event_id" class="form-label">Sự kiện</label>
            <select name="event_id" id="event_id" class="form-select" required>
                <option value="">-- Chọn sự kiện --</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Người dùng</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">-- Chọn người dùng --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->full_name }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Vai trò</label>
            <select name="role_id" id="role_id" class="form-select" required>
                <option value="">-- Chọn vai trò --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->title }}


        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Tạo đăng ký</button>
    </form>
</div>
