@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Thêm loại liên kết tài nguyên</h2>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{ route('admin.resource-link-types.store') }}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="name" class="form-label">Tên loại liên kết tài nguyên</label>
                        <input id="name" name="title" type="text" class="form-control" placeholder="Nhập tên loại liên kết tài nguyên" value="{{ old('name') }}" required>
                        @if ($errors->has('name'))
                            <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <label for="code" class="form-label">Mã loại liên kết tài nguyên</label>
                        <input id="code" name="code" type="text" class="form-control" placeholder="Nhập mã loại liên kết tài nguyên" value="{{ old('code') }}" required>
                        @if ($errors->has('code'))
                            <div class="text-danger mt-2">{{ $errors->first('code') }}</div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <label for="viewcode" class="form-label">Code hiện tài nguyên</label>
                        <input id="viewcode" name="viewcode" type="text" class="form-control" placeholder="Nhập code hiện tài nguyên" value="{{ old('viewcode') }}" required>
                        @if ($errors->has('viewcode'))
                            <div class="text-danger mt-2">{{ $errors->first('viewcode') }}</div>
                        @endif
                    </div>

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                        <a href="{{ route('admin.resource-link-types.index') }}" class="btn btn-secondary w-24">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
