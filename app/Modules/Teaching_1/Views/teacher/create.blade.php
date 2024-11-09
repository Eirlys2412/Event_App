@extends('backend.layouts.master')
@section('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Giảng Viên
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.teacher.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="mgv" class="form-label">Mã Giảng Viên</label>
                    <input id="mgv" name="mgv" type="text" class="form-control" placeholder="Mã giảng viên" value="{{ old('mgv') }}">
                </div>

                <div class="mt-3">
                    <label for="ma_donvi" class="form-label">Đơn Vị</label>
                    <select name="ma_donvi" id="ma_donvi" class="form-select">
                        @foreach($donVis as $donVi)
                            <option value="{{ $donVi->id }}" {{ old('ma_donvi') == $donVi->id ? 'selected' : '' }}>{{ $donVi->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="user_id" class="form-label">Người Dùng</label>
                    <select name="user_id" id="user_id" class="form-select">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="chuyen_nganh" class="form-label">Chuyên Ngành</label>
                    <select name="chuyen_nganh" id="chuyen_nganh" class="form-select">
                        @foreach($chuyenNganhs as $chuyenNganh)
                            <option value="{{ $chuyenNganh->id }}" {{ old('chuyen_nganh') == $chuyenNganh->id ? 'selected' : '' }}>{{ $chuyenNganh->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="hoc_ham" class="form-label">Học Hàm</label>
                    <input id="hoc_ham" name="hoc_ham" type="text" class="form-control" placeholder="Học hàm" value="{{ old('hoc_ham') }}">
                </div>

                <div class="mt-3">
                    <label for="hoc_vi" class="form-label">Học Vị</label>
                    <input id="hoc_vi" name="hoc_vi" type="text" class="form-control" placeholder="Học vị" value="{{ old('hoc_vi') }}">
                </div>
                <div class="mt-3">
                    <label for="loai_giangvien" class="form-label">Loại Giảng Viên</label>
                    <select id="loai_giangvien" name="loai_giangvien" class="form-control">
                        <option value="">Chọn loại giảng viên</option>
                        <option value="1" {{ old('loai_giangvien') == '1' ? 'selected' : '' }}>Giảng viên hạng 1</option>
                        <option value="2" {{ old('loai_giangvien') == '2' ? 'selected' : '' }}>Giảng viên hạng 2</option>
                        <option value="3" {{ old('loai_giangvien') == '3' ? 'selected' : '' }}>Giảng viên hạng 3</option>
                        <option value="4" {{ old('loai_giangvien') == '4' ? 'selected' : '' }}>Giảng viên hạng 4</option>
                    </select>
                </div>
                

                {{-- <div class="mt-3">
                    <label for="loai_giangvien" class="form-label">Loại Giảng Viên</label>
                    <input id="loai_giangvien" name="loai_giangvien" type="text" class="form-control" placeholder="Loại giảng viên" value="{{ old('loai_giangvien') }}">
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
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
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
