@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
 
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Điều chỉnh chi tiết chương trình
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->

        <form method="post" action="{{ route('admin.program_details.update', $program_details) }}">
            @csrf
            @method('patch')
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="hoc_phan" class="form-label">Học phần</label>
                    <select name="hocphan_id" id="hoc_phan" class="form-select">
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" {{ $hoc_phan->id == $program_details->hocphan_id ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mt-3">
                    <label for="ctdt" class="form-label">Chương trình đào tạo</label>
                    <select name="chuongtrinh_id" id="ctdt" class="form-select">
                        @foreach($chuongTrinhdaotao as $ctdt)
                            <option value="{{ $ctdt->id }}" {{ $ctdt->id == $program_details->chuongtrinh_id ? 'selected' : '' }}>
                                {{ $ctdt->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mt-3">
                    <label for="hoc_ky" class="form-label">Học kỳ</label>
                    <select id="hoc_ky" name="hoc_ky_id" class="form-control">
                        <option value="">Chọn học kỳ</option>
                        @foreach($hocKy as $hoc_ky)
                            <option value="{{ $hoc_ky->id }}" {{  $hoc_ky->id == $program_details->hoc_ky_id ? 'selected' : ''}}>
                                {{ $hoc_ky->so_hoc_ky }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mt-3">
                    <label for="loai_hoc_phan" class="form-label">Loại</label>
                    <select id="loai_hoc_phan" name="loai" class="form-control">
                        <option value="">Chọn loại học phần</option>
                        <option value="Bắt buộc" {{ old('loai', $program_details->loai ?? '') == 'Bắt buộc' ? 'selected' : '' }}>
                            Bắt buộc
                        </option>
                        <option value="Tự chọn" {{ old('loai', $program_details->loai ?? '') == 'Tự chọn' ? 'selected' : '' }}>
                            Tự chọn
                        </option>
                    </select>
                </div>
        
                <!-- Học phần tiên quyết -->
                <div class="mt-3">
                    <label for="hocphantienquyet" class="form-label">Học phần tiên quyết</label>
                    <select name="hocphantienquyet[]" id="hocphantienquyet" class="form-select" multiple>
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" 
                                {{ in_array($hoc_phan->id, $hocphantienquyet_ids) ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Học phần song song -->
                <div class="mt-3">
                    <label for="hocphansongsong" class="form-label">Học phần song song</label>
                    <select name="hocphansongsong[]" id="hocphansongsong" class="form-select" multiple>
                        @foreach($hocPhan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" 
                                {{ in_array($hoc_phan->id, $hocphansongsong_ids) ? 'selected' : '' }}>
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
     // Khởi tạo cho học phần tiên quyết
     var selectTienQuyet = new TomSelect('#hocphantienquyet', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd: function() {
            this.setTextboxValue('');
            this.refreshOptions();
        },
        create: true
    });

    // Khởi tạo cho học phần song song
    var selectSongSong = new TomSelect('#hocphansongsong', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd: function() {
            this.setTextboxValue('');
            this.refreshOptions();
        },
        create: true
    });
    
</script>

@endsection