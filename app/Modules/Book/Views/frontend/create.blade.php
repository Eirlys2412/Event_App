@extends('frontend.layouts.master')

@section('content')
    <div class="container">
        <h1>Thêm Sách Mới</h1>
        <form action="{{ route('front.book.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="photo">Ảnh bìa</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>

            <div class="form-group">
                <label for="summary">Tóm tắt</label>
                <textarea name="summary" id="summary" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="content">Nội dung</label>
                <textarea name="content" id="content" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="book_type_id">Loại sách</label>
                <select name="book_type_id" id="book_type_id" class="form-control">
                    @foreach ($bookTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="document">Tài liệu đính kèm</label>
                <input type="file" id="document" name="document[]"
                    class="form-control-file @error('document.*') is-invalid @enderror" multiple>
                @error('document.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Thêm Sách</button>
        </form>
    </div>
@endsection
