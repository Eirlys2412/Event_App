@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Chỉnh sửa Loại Sự Kiện</h1>

    {{-- Form chỉnh sửa --}}
    <form action="{{ route('admin.event_type.update', $EventTypes->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tên loại sự kiện --}}
        <div class="form-group">
            <label for="title">Tên Loại Sự Kiện</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $EventTypes->title) }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Slug --}}
        <div class="form-group">
            <label for="slug">Slug Loại Sự Kiện</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $EventTypes->slug) }}" required>
            @error('slug')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Loại địa điểm --}}
        <div class="form-group">
            <label for="location_type">Loại Địa Điểm</label>
            <select name="location_type" class="form-control" required>
                <option value="outdoor" {{ old('location_type', $EventTypes->location_type) == 'outdoor' ? 'selected' : '' }}>Ngoài trời</option>
                <option value="indoor" {{ old('location_type', $EventTypes->location_type) == 'indoor' ? 'selected' : '' }}>Trong nhà</option>
            </select>
            @error('location_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Địa chỉ --}}
        <div class="form-group">
            <label for="location_address">Địa Chỉ</label>
            <textarea name="location_address" class="form-control">{{ old('location_address', $EventTypes->location_address) }}</textarea>
            @error('location_address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div class="form-group">
            <label for="status">Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status', $EventTypes->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ old('status', $EventTypes->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút hành động --}}
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.event_type.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
