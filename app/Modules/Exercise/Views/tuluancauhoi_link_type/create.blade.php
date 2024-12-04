{{-- resources/views/linktype/create.blade.php --}}
@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thêm loại liên kết câu hỏi tự luận</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form method="post" action="{{ route('admin.tuluancauhoi-link-types.store') }}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="title" class="form-label">Tên loại liên kết</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tên loại liên kết" value="{{ old('title') }}" required>
                    </div>

                    <div class="mt-4">
                        <label for="code" class="form-label">Mã loại liên kết</label>
                        <input id="code" name="code" type="text" class="form-control" placeholder="Nhập mã loại liên kết" value="{{ old('code') }}" required>
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                        <a href="{{ route('admin.tuluancauhoi-link-types.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection