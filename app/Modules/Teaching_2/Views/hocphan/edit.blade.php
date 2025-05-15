@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Sửa học phần
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.hocphan.update', $hocphan->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input value="{{$hocphan->title}}" name="title" type="text" class="form-control" placeholder="tiêu đề">
                    </div>
                    <div class="mt-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" id="photo" name="photo" />
                        @if($hocphan->photo)
                            <div class="mt-2">
                                <img class="tooltip rounded-full" src="{{asset('storage/'.$hocphan->photo)}}" alt="Hình ảnh hiện tại" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Mã học phần</label>
                        <input value="{{$hocphan->code}}" name="code" type="text" class="form-control" placeholder="mã học phần">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Nội dung</label>
                        <textarea class="editor" name="content" id="editor2">{{ old('content', $hocphan->content) }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Tóm tắt</label>
                        <textarea class="form-control" id="editor1" name="summary">{{ old('summary', $hocphan->summary) }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tín chỉ</label>
                        <input value="{{$hocphan->tinchi}}" name="tinchi" type="text" class="form-control" placeholder="tín chỉ">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Hình thức thi</label>
                        <select name="hinhthucthi" class="form-select mt-2">
                            @foreach($hinhthucthi as $data)
                                <option value="{{$data->title}}">{{ $data->title }}</option>
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