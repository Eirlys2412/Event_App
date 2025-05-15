@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Lớp Học
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.class.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="class_name" class="form-label">Tên Lớp</label>
                    <input id="class_name" name="class_name" type="text" class="form-control" placeholder="Tên lớp" value="{{ old('class_name') }}" required>
                </div>

                <div class="mt-3">
                    <label for="teacher_id" class="form-label">Giảng Viên</label>
                    <select name="teacher_id" id="teacher_id" class="form-select">
                        <option value="">Chọn giảng viên</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->full_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                

                <div class="mt-3">
                    <label for="nganh_id" class="form-label">Ngành</label>
                    <select name="nganh_id" id="nganh_id" class="form-select">
                        <option value="">Chọn ngành</option>
                        @foreach($nganhs as $nganh)
                            <option value="{{ $nganh->id }}" {{ old('nganh_id') == $nganh->id ? 'selected' : '' }}>{{ $nganh->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="description" class="form-label">Mô Tả</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Mô tả ngắn về lớp học">{{ old('description') }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="max_students" class="form-label">Số Lượng Sinh Viên Tối Đa</label>
                    <input id="max_students" name="max_students" type="number" class="form-control" placeholder="Số lượng tối đa" value="{{ old('max_students', 30) }}" min="1" required>
                </div>

                <div class="mt-3">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    new TomSelect("#teacher_id");
    new TomSelect("#nganh_id");
</script>
@endsection
