@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh sửa nhóm người dùng
    </h2>

    <!-- Form chỉnh sửa nhóm -->
    <div class="intro-y box p-5 mt-5">
        <form action="{{ route('admin.group.update', $group->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tiêu đề -->
            <div class="mt-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" name="title" class="form-control w-full" id="title" value="{{ old('title', $group->title) }}" required>
                @error('title')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mt-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control w-full" id="slug" value="{{ old('slug', $group->slug) }}" required>
                @error('slug')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div class="mt-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-control w-full">
                    <option value="active" {{ old('status', $group->status) == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                    <option value="inactive" {{ old('status', $group->status) == 'inactive' ? 'selected' : '' }}>Không Hoạt Động</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Riêng tư -->
            <div class="mt-3">
                <label for="private" class="form-label">Riêng tư</label>
                <select name="private" id="private" class="form-control w-full">
                    <option value="1" {{ old('private', $group->private) == 1 ? 'selected' : '' }}>Riêng Tư</option>
                    <option value="0" {{ old('private', $group->private) == 0 ? 'selected' : '' }}>Công Khai</option>
                </select>
                @error('private')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="mt-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" class="form-control w-full" id="description" rows="4">{{ old('description', $group->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ảnh hiện tại -->
            <div class="mt-3">
                <label class="form-label">Ảnh hiện tại</label>
                @if ($group->image)
                    <div class="flex items-center">
                        <img src="{{ asset('storage/' . $group->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded mr-4">
                    </div>
                @else
                    <p>Không có ảnh nào.</p>
                @endif
            </div>

            <!-- Tải lên ảnh mới -->
            <div class="mt-3">
                <label for="image" class="form-label">Tải lên ảnh mới</label>
                <input type="file" name="image" class="form-control w-full" id="image" accept="image/*">
                @error('image')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nút Lưu -->
            <div class="mt-5 text-right">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
@endsection
