@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm người dùng đang học
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.learning.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="user_id" class="form-label">Người học</label>
                        <select id="user_id" name="user_id" class="form-select mt-2">
                            @foreach ($user as $data)
                                <option value="{{ $data->id }}" data-full-name="{{ $data->full_name }}">{{ $data->full_name }}</option>
                            @endforeach
                        </select>   
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Phân công</label>
                        <select id = "phancong_id" name="phancong_id" class="form-select mt-2">
                            @foreach ($phancong as $data)
                                <option value="{{ $data->id }}">Mã giảng viên: {{ $data->giangvien->mgv }}, Môn học: {{ $data->hocphan->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="time-spending" class="form-label">Thời gian học (phút)</label>
                        <select id="time-spending" name="time_spending" class="form-select mt-2" required>
                            <option value="" disabled selected>Chọn thời gian học</option>
                            <option value="15">15 phút</option>
                            <option value="30">30 phút</option>
                            <option value="45">45 phút</option>
                            <option value="60">60 phút</option>
                            <option value="120">120 phút</option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Trạng thái</label>
                        <select id = "status" name="status" class="form-select mt-2">
                            <option value="started">started</option>
                            <option value="done">done</option>
                        </select>
                    </div>
                    <style>
                        .form-check {
                            display: block; /* Đảm bảo mỗi checkbox là một khối riêng */
                        }
                    </style>                                       
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
    var select = new TomSelect('#select-junk',{
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd:function(){
                this.setTextboxValue('');
                this.refreshOptions();
            },
        create: true
        
    });
    select.clear();
</script>

<script>
 Dropzone.autoDiscover = false;
    
    // Dropzone class:
  
        Dropzone.instances[0].options.multiple = false;
        Dropzone.instances[0].options.autoQueue= true;
        Dropzone.instances[0].options.maxFilesize =  1; // MB
        Dropzone.instances[0].options.maxFiles =1;
        Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
        Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
        Dropzone.instances[0].options.previewTemplate =  '<div class=" d-flex flex-column  position-relative">'
                                        +' <img    data-dz-thumbnail >'
                                        
                                    +' </div>';
        // Dropzone.instances[0].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
        Dropzone.instances[0].options.addRemoveLinks =  true;
        Dropzone.instances[0].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        Dropzone.instances[0].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
        });
        Dropzone.instances[0].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        $('#photo').val(response.link);
        console.log('File success successfully!' +response.link);
        });
        Dropzone.instances[0].on("removedfile", function (file ) {
        $('#photo').val('');
        console.log('File removed successfully!'  );
        });
        Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);

        console.log('error !' +message);
        });
        console.log(Dropzone.instances[0].options   );

        // console.log(Dropzone.optionsForElement);

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

</script>

 
@endsection