@extends('backend.layouts.master')

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm loại sách
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form action="{{ route('admin.booktypes.store') }}" method="POST">
                @csrf
                <div class="intro-y box p-5">
                    <!-- Tên loại sách -->
                    <div class="mt-3">
                        <label for="title" class="form-label">Tên loại sách</label>
                        <input id="title" name="title" type="text" class="form-control"
                            placeholder="Nhập tên loại sách" required value="{{ old('title') }}">
                        @error('title')
                            <div class="text-danger mt-2">{{ $message }}</div>
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

                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('admin.booktypes.index') }}" class="btn btn-secondary ml-2">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
