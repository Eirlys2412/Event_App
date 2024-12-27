@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh Sửa Loại Chứng Chỉ
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="intro-y box p-5">
                <form action="{{ route('loai_chungchi.update', $loaiChungchi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-6">
                        <!-- Tiêu đề -->
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $loaiChungchi->title) }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Trạng thái -->
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active" {{ old('status', $loaiChungchi->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $loaiChungchi->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nút submit -->
                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-primary">Cập Nhật Loại Chứng Chỉ</button>
                        <a href="{{ route('loai_chungchi.index') }}" class="btn btn-secondary ml-2">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
