@extends('backend.layouts.master')
@section ('scriptop')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thêm loại tài nguyên</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.resource-types.store') }}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="name" class="form-label">Tên loại tài nguyên</label>
                        <input id="name" name="title" type="text" class="form-control" placeholder="Nhập tên loại tài nguyên" value="{{ old('title') }}" required>
                    </div>

                    <div class="mt-4">
                        <label for="code" class="form-label">Mã loại tài nguyên</label>
                        <input id="code" name="code" type="text" class="form-control" placeholder="Nhập mã loại tài nguyên" value="{{ old('code') }}" required>
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                        <a href="{{ route('admin.resource-types.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
