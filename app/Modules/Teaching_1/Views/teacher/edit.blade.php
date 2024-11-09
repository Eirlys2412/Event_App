@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
 
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Điều chỉnh thông tin Giảng Viên
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.teacher.update', $teacher->id) }}">
            @csrf
            @method('patch')
            <div class="intro-y box p-5">
                <div>
                    <label for="mgv" class="form-label">Mã Giảng Viên</label>
                    <input id="mgv" name="mgv" type="text" value="{{ $teacher->mgv }}" class="form-control" placeholder="Mã giảng viên" required>
                </div>

                <div class="mt-3">
                    <label for="ma_donvi" class="form-label">Đơn Vị</label>
                    <select name="ma_donvi" id="ma_donvi" class="form-select">
                        @foreach($donVis as $donVi)
                            <option value="{{ $donVi->id }}" {{ $donVi->id == $teacher->ma_donvi ? 'selected' : '' }}>{{ $donVi->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="user_id" class="form-label">Người Dùng</label>
                    <select name="user_id" id="user_id" class="form-select">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $teacher->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="chuyen_nganh" class="form-label">Chuyên Ngành</label>
                    <select name="chuyen_nganh" id="chuyen_nganh" class="form-select">
                        @foreach($chuyenNganhs as $chuyenNganh)
                            <option value="{{ $chuyenNganh->id }}" {{ $chuyenNganh->id == $teacher->chuyen_nganh ? 'selected' : '' }}>{{ $chuyenNganh->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="hoc_ham" class="form-label">Học Hàm</label>
                    <input id="hoc_ham" name="hoc_ham" type="text" value="{{ $teacher->hoc_ham }}" class="form-control" placeholder="Học hàm">
                </div>

                <div class="mt-3">
                    <label for="hoc_vi" class="form-label">Học Vị</label>
                    <input id="hoc_vi" name="hoc_vi" type="text" value="{{ $teacher->hoc_vi }}" class="form-control" placeholder="Học vị">
                </div>
                <div class="mt-3">
                    <label for="loai_giangvien" class="form-label">Loại Giảng Viên</label>
                    <select id="loai_giangvien" name="loai_giangvien" class="form-control">
                        <option value="">Chọn loại giảng viên</option>
                        <option value="1" {{ (old('loai_giangvien', $teacher->loai_giangvien) == '1') ? 'selected' : '' }}>Giảng viên hạng 1</option>
                        <option value="2" {{ (old('loai_giangvien', $teacher->loai_giangvien) == '2') ? 'selected' : '' }}>Giảng viên hạng 2</option>
                        <option value="3" {{ (old('loai_giangvien', $teacher->loai_giangvien) == '3') ? 'selected' : '' }}>Giảng viên hạng 3</option>
                        <option value="4" {{ (old('loai_giangvien', $teacher->loai_giangvien) == '4') ? 'selected' : '' }}>Giảng viên hạng 4</option>
                    </select>
                </div>
                
                {{-- <div class="mt-3">
                    <label for="loai_giangvien" class="form-label">Loại Giảng Viên</label>
                    <input id="loai_giangvien" name="loai_giangvien" type="text" value="{{ $teacher->loai_giangvien }}" class="form-control" placeholder="Loại giảng viên">
                </div> --}}

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
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script>
</script>

@endsection