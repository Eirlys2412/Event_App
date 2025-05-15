@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm Nhóm Người Dùng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.group.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề" value="{{ old('title') }}" required>
                    </div>

                    <div class="mt-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input id="slug" name="slug" type="text" class="form-control" placeholder="Nhập slug" value="{{ old('slug') }}">
                    </div>

                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="editor" name="description" id="editor2">{{ old('description') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" class="form-select mt-2">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="private" class="form-label">Riêng tư</label>
                        <select name="private" class="form-select mt-2">
                            <option value="0" {{ old('private') == 0 ? 'selected' : '' }}>Công khai</option>
                            <option value="1" {{ old('private') == 1 ? 'selected' : '' }}>Riêng tư</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="image" class="form-label">Hình ảnh (nếu có)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor2'), {
                ckfinder: {
                    uploadUrl: '{{ route("admin.upload.ckeditor")."?_token=".csrf_token() }}'
                },
                mediaEmbed: { previewsInData: true }
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        var select = new TomSelect('#tags', {
            create: true,
            plugins: ['remove_button'],
            onItemAdd: function(value) {
                this.setTextboxValue('');
                this.refreshOptions();
            }
        });
    </script>
@endsection
