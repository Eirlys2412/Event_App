@extends('backend.layouts.master')
@section('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm chi tiết chương trình
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.program_details.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="hoc_phan" class="form-label">Học phần</label>
                    <select name="hocphan_id" id="hoc_phan" class="form-select">
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" {{ old('hoc_phan') == $hoc_phan->id ? 'selected' : '' }}>{{ $hoc_phan->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="ctdt" class="form-label">Chương trình đào tạo</label>
                    <select name="chuongtrinh_id" id="ctdt" class="form-select">
                        @foreach($chuongTrinhdaotao as $ctdt)
                            <option value="{{ $ctdt->id }}" {{ old('ctdt') == $ctdt->id ? 'selected' : '' }}>{{ $ctdt->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="hoc_ky" class="form-label">Học kỳ</label>
                    <select id="hoc_ky" name="hocky" class="form-control">
                        <option value="">Chọn học kỳ</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" >
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="mt-3">
                    <label for="loai" class="form-label">Loại</label>
                    <select id="loai_hoc_phan" name="loai" class="form-control">
                        <option value="">Chọn loại học phần</option>
                        <option value="Bắt buộc">Bắt buộc</option>
                        <option value="Tự chọn">Tự chọn</option>
                    </select>
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
</script>

@endsection
