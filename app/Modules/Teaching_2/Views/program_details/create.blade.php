@extends('backend.layouts.master')
@section('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm chi tiết chương trình
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.program_details.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <!-- Chọn Học Phần -->
                <div class="mt-3">
                    <label for="hoc_phan" class="form-label">Học phần</label>
                    <select name="hocphan_id" id="hoc_phan" class="form-select">
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" {{ old('hocphan_id') == $hoc_phan->id ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Chọn Chương Trình Đào Tạo -->
                <div class="mt-3">
                    <label for="ctdt" class="form-label">Chương trình đào tạo</label>
                    <select name="chuongtrinh_id" id="ctdt" class="form-select">
                        @foreach($chuongTrinhdaotao as $ctdt)
                            <option value="{{ $ctdt->id }}" {{ old('chuongtrinh_id') == $ctdt->id ? 'selected' : '' }}>
                                {{ $ctdt->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Chọn Học Kỳ -->
                <div class="mt-3">
                    <label for="hoc_ky" class="form-label">Học kỳ</label>
                    <select id="hoc_ky" name="hocky" class="form-control">
                        <option value="">Chọn học kỳ</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('hocky') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
        
                <!-- Chọn Loại -->
                <div class="mt-3">
                    <label for="loai" class="form-label">Loại</label>
                    <select id="loai_hoc_phan" name="loai" class="form-control">
                        <option value="">Chọn loại học phần</option>
                        <option value="Bắt buộc" {{ old('loai') == 'Bắt buộc' ? 'selected' : '' }}>Bắt buộc</option>
                        <option value="Tự chọn" {{ old('loai') == 'Tự chọn' ? 'selected' : '' }}>Tự chọn</option>
                    </select>
                </div>
        
                <!-- Học Phần Tiên Quyết -->
                <div class="mt-3">
                    <label for="hocphantienquyet" class="form-label">Học phần tiên quyết:</label>
                    <select name="hocphantienquyet[]" id="hocphantienquyet" class="form-select" multiple>
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" {{ in_array($hoc_phan->id, old('hocphantienquyet', [])) ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Học Phần Song Song -->
                <div class="mt-3">
                    <label for="hocphansongsong" class="form-label">Học phần song song:</label>
                    <select name="hocphansongsong[]" id="hocphansongsong" class="form-select" multiple>
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" {{ in_array($hoc_phan->id, old('hocphansongsong', [])) ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Hiển thị lỗi nếu có -->
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
        
                <!-- Nút Submit -->
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
    $(document).ready(function() {
    $('#hocphantienquyet').select2({
        placeholder: "Chọn các học phần tiên quyết",
        allowClear: true
    });

    $('#hocphansongsong').select2({
        placeholder: "Chọn các học phần song song",
        allowClear: true
    });
});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/i18n/vi.min.js"></script>

@endsection
