@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh Sửa Phân Công
    </h2>
    
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="intro-y box p-5 mt-5">
                <form action="{{ route('phancong.update', $phancong->id) }}" method="post">
                    @csrf
                    @method('PUT') <!-- Phương thức PUT để cập nhật dữ liệu -->
                    
                    <!-- Giảng viên -->
                    <div class="mb-4">
                        <label for="giangvien_id" class="form-label">Giảng viên</label>
                        <select name="giangvien_id" id="giangvien_id" class="form-control">
                            <option value="">Chọn Giảng viên</option>
                            @foreach($giangviens as $teacher)
                            <option value="{{ $teacher->id }}" {{ $teacher->id == old('teacher_id', $phancong->teacher_id) ? 'selected' : '' }}>
                                {{ $teacher->user->full_name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                        @error('giangvien_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Học phần -->
                    <div class="mb-4">
                        <label for="hocphan_id" class="form-label">Học phần</label>
                        <select name="hocphan_id" id="hocphan_id" class="form-control">
                            <option value="">Chọn Học phần</option>
                            @foreach($hocphans as $hocphan)
                                <option value="{{ $hocphan->id }}" {{ $phancong->hocphan_id == $hocphan->id ? 'selected' : '' }}>
                                    {{ $hocphan->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('hocphan_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Học kỳ -->
                    <div class="mb-4">
                        <label for="hocky_id" class="form-label">Học kỳ</label>
                        <select name="hocky_id" id="hocky_id" class="form-control">
                            <option value="">Chọn Học kỳ</option>
                            @foreach($hockys as $hocky)
                                <option value="{{ $hocky->id }}" {{ $phancong->hocky_id == $hocky->id ? 'selected' : '' }}>
                                    {{ $hocky->so_hoc_ky }}
                                </option>
                            @endforeach
                        </select>
                        @error('hocky_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Năm học -->
                    <div class="mb-4">
                        <label for="namhoc_id" class="form-label">Năm học</label>
                        <select name="namhoc_id" id="namhoc_id" class="form-control">
                            <option value="">Chọn Năm học</option>
                            @foreach($namhocs as $namhoc)
                                <option value="{{ $namhoc->id }}" {{ $phancong->namhoc_id == $namhoc->id ? 'selected' : '' }}>
                                    {{ $namhoc->nam_hoc }}
                                </option>
                            @endforeach
                        </select>
                        @error('namhoc_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lớp -->
                    <div class="mb-4">
                        <label for="class_id" class="form-label">Lớp</label>
                        <select name="class_id" id="class_id" class="form-control">
                            <option value="">Chọn Lớp</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $phancong->class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Ngày phân công -->
                    <div class="mb-4">
                        <label for="ngayphancong" class="form-label">Ngày phân công</label>
                        <input type="date" name="ngayphancong" id="ngayphancong" class="form-control" value="{{ old('ngayphancong', $phancong->ngayphancong) }}" required>
                        @error('ngayphancong')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="mb-4">
                        <label for="time_start" class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="time_start" id="time_start" class="form-control" value="{{ old('time_start', $phancong->time_start) }}">
                        @error('time_start')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="mb-4">
                        <label for="time_end" class="form-label">Ngày kết thúc</label>
                        <input type="date" name="time_end" id="time_end" class="form-control" value="{{ old('time_end', $phancong->time_end) }}">
                        @error('time_end')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Số lượng sinh viên tối đa -->
                    <div class="mb-4">
                        <label for="max_student" class="form-label">Số lượng sinh viên tối đa</label>
                        <input type="text" name="max_student" id="max_student" class="form-control" value="{{ old('max_student', $phancong->max_student) }}">
                        @error('max_student')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 text-center">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('phancong.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
