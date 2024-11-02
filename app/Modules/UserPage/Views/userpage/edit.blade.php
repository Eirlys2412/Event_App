@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 
<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')
 
    <div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Điều chỉnh trang người dùng
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.userpage.update', $userpage->id) }}">
            @csrf
            @method('patch')
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" value="{{ $userpage->title }}" class="form-control" placeholder="title" required>
                </div>

                <div class="mt-3">
                    <label for="summary" class="form-label">Mô tả ngắn</label>
                    <textarea class="form-control" name="summary" id="editor1">{{ $userpage->summary }}</textarea>
                </div>

                {{-- <div class="mt-3">
                    <label for="items" class="form-label">Nội dung</label>
                    <textarea class="editor" name="items" id="editor2">{{ $userpage->items }}</textarea>
                </div> --}}

                {{-- <div class="mt-3">
                    <label class="form-select-label" for="status">Danh mục</label>
                    <select name="cat_id" class="form-select mt-2 sm:mr-2">
                        <option value="">- Chọn danh mục -</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $userpage->cat_id ? 'selected' : '' }}>
                            {{ $cat->title }}
                        </option>
                        @endforeach
                    </select>
                </div> --}}

                {{-- <div class="mt-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="tags" name="tag_ids[]" multiple placeholder="..." autocomplete="off">
                        @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" 
                            @foreach($tag_ids as $item)
                                @if($item->tag_id == $tag->id) selected @endif
                            @endforeach
                        >{{ $tag->title }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="mt-3">
                    <label class="form-select-label" for="status">Tình trạng</label>
                    <select name="status" class="form-select mt-2 sm:mr-2">
                        <option value="active" {{ $userpage->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $userpage->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
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
   
</script>
 
<script>
    $(".btn_remove").click(function(){
        $(this).parent().parent().remove();   
        var link_photo = "";
        $('.product_photo').each(function() {
            if (link_photo != '')
            {
            link_photo+= ',';
            }   
            link_photo += $(this).data("photo");
        });
        $('#photo_old').val(link_photo);
    });

 
                // previewsContainer: ".dropzone-previews",
    Dropzone.instances[0].options.multiple = true;
    Dropzone.instances[0].options.autoQueue= true;
    Dropzone.instances[0].options.maxFilesize =  1; // MB
    Dropzone.instances[0].options.maxFiles =5;
    Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
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
        {
            var value_link = $('#photo').val();
            if(value_link != "")
            {
                value_link += ",";
            }
            value_link += response.link;
            $('#photo').val(value_link);
        }
           
        // console.log('File success successfully!' +$('#photo').val());
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
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor2' ), 
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