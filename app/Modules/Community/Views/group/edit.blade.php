@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa nhóm cộng đồng</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">
            <form method="post" action="{{ route('admin.community.groups.update', $group->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mt-3">
                    <label for="name" class="form-label">Tên nhóm <span class="text-danger">*</span></label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="Nhập tên nhóm" value="{{ old('name', $group->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn về nhóm">{{ old('description', $group->description) }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="cover_image" class="form-label">Ảnh bìa</label>
                    @if($group->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $group->cover_image) }}" alt="Cover Image" class="w-32 h-20 object-cover">
                        </div>
                    @endif
                    <input id="cover_image" type="file" name="cover_image" class="form-control" accept="image/*">
                    <div class="text-xs text-slate-500 mt-1">Để trống nếu không muốn thay đổi ảnh bìa hiện tại</div>
                    @error('cover_image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="privacy" class="form-label">Quyền riêng tư <span class="text-danger">*</span></label>
                    <select id="privacy" name="privacy" class="form-select" required>
                        <option value="public" {{ old('privacy', $group->privacy) == 'public' ? 'selected' : '' }}>Công khai</option>
                        <option value="private" {{ old('privacy', $group->privacy) == 'private' ? 'selected' : '' }}>Riêng tư</option>
                        <option value="hidden" {{ old('privacy', $group->privacy) == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('privacy')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status', $group->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status', $group->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-right mt-5">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" onclick="window.history.back();">Hủy</button>
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </form>
        </div>
        <!-- END: Form Layout -->
    </div>
</div>

@endsection 