@extends('backend.layouts.master')

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa bài đăng</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <div class="intro-y box p-5">
            <form method="post" action="{{ route('admin.community.posts.update', $post->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mt-3">
                    <label for="group_id" class="form-label">Chọn nhóm <span class="text-danger">*</span></label>
                    <select id="group_id" name="group_id" class="form-select" required>
                        <option value="">-- Chọn nhóm --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $post->group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('group_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mt-3">
                    <label for="title" class="form-label">Tiêu đề bài đăng <span class="text-danger">*</span></label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề bài đăng" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea id="content" name="content" class="form-control" rows="5" placeholder="Nhập nội dung bài đăng">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="images" class="form-label">Hình ảnh</label>
                    <input id="images" type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <div class="text-xs text-slate-500 mt-1">Có thể chọn nhiều ảnh (tối đa 5 ảnh). Để lại trống nếu không thay đổi.</div>
                    @error('images')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="inactive" {{ old('status', $post->status) == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-right mt-5">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" onclick="window.history.back();">Hủy</button>
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </form>
        </div>
        <!-- END: Form Layout -->
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('backend/js/ckeditor.js') }}"></script>
<script>
    $(document).ready(function() {
        if (document.querySelector('#content')) {
            ClassicEditor
                .create(document.querySelector('#content'))
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endsection