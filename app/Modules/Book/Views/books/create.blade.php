@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Tạo Sách Mới
    </h2>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mt-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề" required>
            @error('title')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label for="photo" class="form-label">Ảnh Bìa</label>
            <input type="file" name="photo" id="photo" class="form-control">
            @error('photo')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </div>
        <div class="mt-3">
            <label for="book_type_id" class="form-label">Loại sách</label>
            <select name="book_type_id" id="book_type_id" class="form-control" required>
                <option value="">Chọn loại sách</option>
                @foreach ($bookTypes as $bookType)
                    <option value="{{ $bookType->id }}" {{ old('book_type_id') == $bookType->id ? 'selected' : '' }}>
                        {{ $bookType->title }}
                    </option>
                @endforeach
            </select>
            @error('book_type_id')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label for="summary" class="form-label">Tóm Tắt</label>
            <textarea name="summary" id="summary" class="form-control" placeholder="Nhập tóm tắt"></textarea>
        </div>

        <div class="mt-3">
            <label for="content" class="form-label">Nội Dung</label>
            <textarea name="content" id="content" class="form-control" placeholder="Nhập nội dung"></textarea>
        </div>

        <div class="mt-3">
            <label for="document" class="form-label">Tài Liệu</label>
            <input type="file" name="document[]" id="document" class="form-control" multiple required>
            @error('document')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label for="status" class="form-label">Trạng Thái</label>
            <select name="status" id="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-3">
            <label for="post-form-4" class="form-label">Tags</label>
            <select id="select-junk" name="tag_ids[]" multiple placeholder=" ..." autocomplete="off">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Tạo Sách</button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        var select = new TomSelect('#select-junk', {
            maxItems: null,
            allowEmptyOption: true,
            plugins: ['remove_button'],
            sortField: {
                field: "text",
                direction: "asc"
            },
            onItemAdd: function() {
                this.setTextboxValue('');
                this.refreshOptions();
            },
            create: true
        });
        select.clear();
    </script>
    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                ckfinder: {
                    uploadUrl: '{{ route('admin.upload.ckeditor') . '?_token=' . csrf_token() }}'
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
