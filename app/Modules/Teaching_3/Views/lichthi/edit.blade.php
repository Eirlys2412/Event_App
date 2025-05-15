@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">

@endsection
@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Sửa lịch thi 
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.lichthi.update', $lichthi->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    
                    <div class="mt-3">
                        <label for="phancong_id" class="form-label">Phân công</label>
                        <select id="phancong_id" name="phancong_id" class="form-select mt-2">
                            @foreach($phancong as $pc)
                                @foreach($teacher as $tc)
                                    @foreach($hocphan as $hp)
                                        @if ($pc->giangvien_id == $tc->id && $pc->hocphan_id == $hp->id)
                                            <option value="{{ $pc->id }}" 
                                                {{ old('phancong_id', $lichthi->phancong_id) == $pc->id ? 'selected' : '' }}>
                                                Mã giảng viên: {{ $tc->mgv }}, Môn học: {{ $hp->title }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="buoi" class="form-label">Buổi</label>
                        <select id="buoi" name="buoi" class="form-control">
                            <option value="">Chọn buổi</option>
                            <option value="Sáng" {{ old('buoi', $lichthi->buoi) == 'Sáng' ? 'selected' : '' }}>Sáng</option>
                            <option value="Chiều" {{ old('buoi', $lichthi->buoi) == 'Chiều' ? 'selected' : '' }}>Chiều</option>
                            <option value="Tối" {{ old('buoi', $lichthi->buoi) == 'Tối' ? 'selected' : '' }}>Tối</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="dia_diem_thi" class="form-label">Địa điểm thi</label>
                        <select name="dia_diem_thi[]" id="dia_diem_thi" class="form-select" multiple>
                            @php
                                $diaDiemCu = json_decode($lichthi->dia_diem_thi, true)['location'] ?? [];
                            @endphp
                            @foreach($diadiemList as $id => $title)
                                <option value="{{ $id }}" 
                                    {{ in_array($id, $diaDiemCu) ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="ngay1" class="form-label">Ngày 1</label>
                        <input name="ngay1" id="ngay1" type="date" 
                            value="{{ old('ngay1', $lichthi->ngay1) }}" 
                            class="form-control" placeholder="Chọn ngày">
                    </div> 

                    <div class="mt-3">
                        <label for="ngay2" class="form-label">Ngày 2</label>
                        <input name="ngay2" id="ngay2" type="date" 
                            value="{{ old('ngay2', $lichthi->ngay2) }}" 
                            class="form-control" placeholder="Chọn ngày">
                    </div> 

                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var selectDiadiem = new TomSelect('#dia_diem_thi', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: { field: "text", direction: "asc" },
        onItemAdd: function() {
            this.setTextboxValue('');
            this.refreshOptions();
        },
        create: true
    });
</script>

@endsection
