@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm câu hỏi Tự luận 
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.tuluancauhoi.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="" class="form-label">Nội dung</label>
                        <textarea class="editor" name="content" id="editor2">{{ old('content') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Học Phần</label>
                        <select name="hocphan_id" class="form-select mt-2">
                            @foreach($hocphan as $data)
                                <option value="{{$data->id}}">{{ $data->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Người tạo</label>
                        <select name="user_id" class="form-select mt-2">
                            @foreach($user as $data)
                                <option value="{{$data->id}}">{{ $data->username }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="post-form-4" class="form-label">Tags</label>
                        <select id="select-junk" name="tag_ids[]" multiple placeholder=" ..." autocomplete="off">
                          @foreach ($tags as $tag )
                          <option value="{{$tag->id}}" >{{$tag->title}}</option>
                          @endforeach
                        </select>
                    </div>  
                    <div class="mt-3">
                        <label for="document" class="form-label">Tài Liệu</label>
                        <input type="file" name="document[]" id="document" class="form-control" multiple required>
                        @error('document')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div> 

                    <!-- Danh sách đáp án -->
                    <div class="mt-3">
                        <label for="answers" class="form-label">Danh sách đáp án</label>
                        <table class="table table-bordered" id="answers-table">
                            <thead>
                                <tr>
                                    <th>Đáp án</th>
                                    <th>Resource</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="answers[0][content]" class="form-control" placeholder="Nhập đáp án">
                                    </td>
                                    <td>
                                        {{-- <select name="answers[0][is_correct]" class="form-select">
                                            <option value="1">Đúng</option>
                                            <option value="0">Sai</option>
                                        </select> --}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-answer">Xóa</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="add-answer">Thêm đáp án</button>
                                               
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

    // Xử lý thêm đáp án mới
    document.getElementById('add-answer').addEventListener('click', function () {
    const tableBody = document.querySelector('#answers-table tbody');
    const rowCount = tableBody.rows.length; // Lấy số dòng hiện tại
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <input type="text" name="answers[${rowCount}][content]" class="form-control" placeholder="Nhập đáp án">
        </td>
        <td>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-answer">Xóa</button>
        </td>
    `;
    tableBody.appendChild(newRow);
});

// Xử lý xóa đáp án
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-answer')) {
        e.target.closest('tr').remove();
    }
});
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