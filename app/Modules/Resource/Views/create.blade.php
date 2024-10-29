@extends('backend.layouts.master')
@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm tài nguyên
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form id="resource-form" method="post" action="{{ route('admin.resources.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label class="form-label">Loại liên kết</label>
                        <div class="mt-2">
                            <input type="radio" id="resource" name="resource_type" value="resource" checked>
                            <label for="resource" class="ml-2">Upload tài nguyên</label>

                            <input type="radio" id="link" name="resource_type" value="link" class="ml-4">
                            <label for="link" class="ml-2">Liên kết tài nguyên</label>
                        </div>
                    </div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề"
                        value="{{ old('title') }}" required>
                    <div class="mt-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả chi tiết">{{ old('description') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Loại tài nguyên</label>
                        <select name="type_code" class="form-select mt-2" required>
                            <option value="">- Chọn loại tài nguyên -</option>
                            @foreach ($resourceTypes as $type)
                                <option value="{{ $type->code }}">{{ $type->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="resourceFields" class="mt-3">
                        <div class="mt-3">
                            <label for="file-input" class="form-label">Tệp phương tiện</label>
                            <input type="file" name="file" class="form-control" id="file-input">
                        </div>
                    </div>

                    <div id="linkFields" class="mt-3 hidden">
                        <label for="" class="form-label">Loại liên kết tài nguyên</label>
                        <select name="link_code" class="form-select mt-2">
                            <option value="">- Chọn loại liên kết -</option>
                            @foreach ($linkTypes as $type)
                                <option value="{{ $type->code }}">{{ $type->title }}</option>
                            @endforeach
                        </select>

                        <div class="mt-3">
                            <label for="" class="form-label">Liên kết</label>
                            <input type="url" name="url" class="form-control"
                                placeholder="Nhập liên kết YouTube (nếu có)" value="{{ old('url') }}">
                        </div>


                    </div>

                    <div class="mt-3">

                        <label for="post-form-4" class="form-label">Tags</label>

                        <select id="select-junk" name="tag_ids[]" multiple placeholder=" ..." autocomplete="off">

                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->title }}</option>
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

@section('scripts')
    <script>
        var select = new TomSelect('#select-junk', {
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
        select.clear();
    
        // JavaScript để ẩn/hiện các trường
        document.querySelectorAll('input[name="resource_type"]').forEach(function(elem) {
            elem.addEventListener("change", function(event) {
                if (event.target.value === "resource") {
                    document.getElementById("resourceFields").classList.remove("hidden");
                    document.getElementById("linkFields").classList.add("hidden");
                } else {
                    document.getElementById("resourceFields").classList.add("hidden");
                    document.getElementById("linkFields").classList.remove("hidden");
                }
            });
        });

        document.getElementById('resource-form').addEventListener('submit', function(event) {
            if (document.querySelector('input[name="resource_type"]:checked').value === 'link') {
                var youtubeUrl = document.querySelector('input[name="youtube_url"]').value.trim();
                var documentUrl = document.querySelector('input[name="document_url"]').value.trim();
                var imageUrl = document.querySelector('input[name="image_url"]').value.trim();

                if (!youtubeUrl && !documentUrl && !imageUrl) {
                    event.preventDefault();
                    alert('Vui lòng nhập ít nhất một liên kết (YouTube, Tài liệu, hoặc Hình ảnh).');
                }
            }
        });

    </script>

    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                ckfinder: {
                    uploadUrl: '{{ route('admin.upload.ckeditor') . '?_token=' . csrf_token() }}'
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>


    
@endsection
