@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm lịch thi
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.lichthi.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Phân công</label>
                        <select id="phancong_id" name="phancong_id" class="form-select mt-2">
                            @foreach($phancong as $pc)
                                @foreach($teacher as $tc)
                                    @foreach($hocphan as $hp)
                                        @if ($pc->giangvien_id == $tc->id && $pc->hocphan_id == $hp->id)
                                            <option value="{{ $pc->id }}">
                                                Giảng viên: {{ $tc->user->full_name ?? 'N/A' }}, Môn học: {{ $hp->title }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>                    


                    <div class="mt-3">
                        <label for="buoi" class="form-label">Buổi</label>
                        <select id="buoi" name="buoi" class="form-select mt-2">
                            <option value="Sáng">Sáng</option>
                            <option value="Chiều">Chiều</option>
                            <option value="Tối">Tối</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="ngay1" class="form-label">Ngày 1</label>
                        <input name="ngay1" id="ngay1" type="date" class="form-control" placeholder="Chọn ngày">
                    </div>  

                    <div class="mt-3">
                        <label for="ngay2" class="form-label">Ngày 2</label>
                        <input name="ngay2" id="ngay2" type="date" class="form-control" placeholder="Chọn ngày">
                    </div>    
                     
                    <!-- Dia diem -->
                    <div class="mt-3">
                        <label for="dia_diem_thi" class="form-label">Địa điểm thi:</label>
                        <select name="dia_diem_thi[]" id="dia_diem_thi" class="form-select" multiple>
                            @foreach($diadiem as $dia_diem)
                                <option value="{{ $dia_diem->id }}" {{ in_array($dia_diem->id, old('dia_diem_thi', [])) ? 'selected' : '' }}>
                                    {{ $dia_diem->title }}
                                </option>
                            @endforeach
                        </select>
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


<script>
    // Khởi tạo cho học phần tiên quyết
    var selectDiadiem = new TomSelect('#dia_diem_thi', {
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
<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
      ClassicEditor
        .create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("admin.upload.ckeditor")."?_token=".csrf_token()}}'
                }
                ,
                mediaEmbed: {previewsInData: true}

        })

        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })

</>

 
@endsection