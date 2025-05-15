@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo nhóm cộng đồng mới</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">
            <form method="post" action="{{ route('admin.community.groups.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mt-3">
                    <label for="name" class="form-label">Tên nhóm <span class="text-danger">*</span></label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="Nhập tên nhóm" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn về nhóm">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="cover_image" class="form-label">Ảnh bìa</label>
                    <input id="cover_image" type="file" name="cover_image" class="form-control" accept="image/*">
                    @error('cover_image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="privacy" class="form-label">Quyền riêng tư <span class="text-danger">*</span></label>
                    <select id="privacy" name="privacy" class="form-select" required>
                        <option value="public" {{ old('privacy') == 'public' ? 'selected' : '' }}>Công khai</option>
                        <option value="private" {{ old('privacy') == 'private' ? 'selected' : '' }}>Riêng tư</option>
                        <option value="hidden" {{ old('privacy') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('privacy')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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

@section('script')
<script>
$(document).ready(function() {
    // Khởi tạo Dropzone cho ảnh đại diện
    var avatarDropzone = new Dropzone("#mydropzone", {
        url: "{{ route('admin.upload.image') }}",
        method: "post",
        maxFiles: 1,
        paramName: "file",
        maxFilesize: 10, // MB
        acceptedFiles: "image/*",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            if (response.success) {
                $('#avatar').val(response.path);
            }
        }
    });

    // Khởi tạo Dropzone cho ảnh bìa
    var coverDropzone = new Dropzone("#cover-dropzone", {
        url: "{{ route('admin.upload.image') }}",
        method: "post",
        maxFiles: 1,
        paramName: "file",
        maxFilesize: 50, // MB
        acceptedFiles: "image/*",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            if (response.success) {
                $('#cover_image').val(response.path);
            }
        }
    });
});
</script>
@endsection 