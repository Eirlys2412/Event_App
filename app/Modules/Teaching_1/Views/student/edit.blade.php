@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa Sinh Viên</h2>
</div>

<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('student.update', $student->id) }}">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">

                <!-- Mã số sinh viên -->
                <div>
                    <label for="mssv" class="form-label">Mã số sinh viên</label>
                    <input id="mssv" name="mssv" type="text" class="form-control" value="{{ $student->mssv }}" required>
                </div>

                <!-- Khóa -->
                <div class="mt-3">
                    <label for="khoa" class="form-label">Khóa</label>
                    <input id="khoa" name="khoa" type="text" class="form-control" value="{{ $student->khoa }}" required>
                </div>

                <!-- Đơn vị -->
                <div class="mt-3">
                    <label for="donvi_id" class="form-label">Đơn vị</label>
                    <select name="donvi_id" class="form-select mt-2" required>
                        @foreach($donvis as $donvi)
                            <option value="{{ $donvi->id }}" {{ $student->donvi_id == $donvi->id ? 'selected' : '' }}>
                                {{ $donvi->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User -->
                <div class="mt-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" class="form-select mt-2" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $student->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ngành -->
                <div class="mt-3">
                    <label for="nganh_id" class="form-label">Ngành</label>
                    <select name="nganh_id" class="form-select mt-2" required>
                        @foreach($nganhs as $nganh)
                            <option value="{{ $nganh->id }}" {{ $student->nganh_id == $nganh->id ? 'selected' : '' }}>
                                {{ $nganh->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Lớp -->
                <div class="mt-3">
                    <label for="class_id" class="form-label">Lớp</label>
                    <select name="class_id" class="form-select mt-2" required>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tình trạng -->
                <div class="mt-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2" required>
                        <option value="đang học" {{ $student->status == 'đang học' ? 'selected' : '' }}>Đang học</option>
                        <option value="thôi học" {{ $student->status == 'thôi học' ? 'selected' : '' }}>Thôi học</option>
                        <option value="tốt nghiệp" {{ $student->status == 'tốt nghiệp' ? 'selected' : '' }}>Tốt nghiệp</option>
                    </select>
                </div>

                {{-- <!-- Trường user_id ẩn -->
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"> --}}

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection
