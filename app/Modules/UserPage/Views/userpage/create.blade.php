@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm User Page
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{route('admin.userpage.store')}}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="regular-form-1" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="title">
                </div>
                
                {{-- <div class="mt-3">
                    <label for="" class="form-label">Slug</label>
                    <input id="slug" name="slug" type="text" class="form-control" placeholder="slug">
                </div> --}}

                <div class="mt-3">
                    <label for="" class="form-label">Mô tả ngắn</label>
                    <textarea class="form-control" id="editor1" name="summary">{{old('summary')}}</textarea>
                </div>

                {{-- <div class="mt-3">
                    <label for="" class="form-label">Nội dung</label>
                    <textarea class="editor" name="items" id="editor2">{{old('items')}}</textarea>
                </div> --}}

                <div class="mt-3">
                    <div class="flex flex-col sm:flex-row items-center">
                        <label style="min-width:70px" class="form-select-label" for="status">Tình trạng</label>
                        <select name="status" class="form-select mt-2 sm:mr-2">
                            <option value="active" {{old('status')=='active'?'selected':''}}>Active</option>
                            <option value="inactive" {{old('status')=='inactive'?'selected':''}}>Inactive</option>
                        </select>
                    </div>
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

<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("admin.upload.ckeditor")."?_token=".csrf_token()}}'
            },
            mediaEmbed: { previewsInData: true }
        })
        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })
</script>

@endsection
