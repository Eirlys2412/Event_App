@extends('backend.layouts.master')

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Sửa loại sách
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form action="{{ route('admin.bookpoints.update', $bookPoint->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="func_cmd">Hành động</label>
                        <input type="text" name="func_cmd" id="func_cmd" class="form-control"
                            value="{{ $bookPoint->func_cmd }}" required>
                    </div>
                    <div class="mt-3">
                        <label for="point">Điểm</label>
                        <input type="number" name="point" id="point" class="form-control"
                            value="{{ $bookPoint->point }}" required>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.bookpoints.index') }}" class="btn btn-secondary ml-2">Hủy</a>
                    </div>
            </form>
        </div>
    @endsection
