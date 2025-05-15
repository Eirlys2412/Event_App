@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
 

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection
@section('content')

 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Sửa thời khóa biểu
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('admin.thoikhoabieu.update', $thoikhoabieu->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Phân công</label>
                        <select id = "phancong_id" name="phancong_id" class="form-select mt-2">
                            @foreach($phancong as $pc)
                                @foreach($teacher as $tc)
                                    @foreach($hocphan as $hp)
                                        @if ($pc->giangvien_id == $tc->id && $pc->hocphan_id == $hp->id)
                                            <option value="{{ $pc->id }}">Mã giảng viên: {{ $tc->mgv }}, Môn học: {{ $hp->title }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="diadiem_id" class="form-label">Địa điểm</label>
                        <select name="diadiem_id" id="diadiem_id" class="form-select">
                            @foreach($diadiem as $dd)
                                <option value="{{ $dd->id }}" {{ $dd->id == $thoikhoabieu->diadiem_id ? 'selected' : '' }}>
                                    {{ $dd->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mt-3">
                        <label for="buoi" class="form-label">Buổi</label>
                        <select id="buoi" name="buoi" class="form-control">
                            <option value="">Chọn buổi</option>
                            <option value="Sáng" {{ old('buoi', $thoikhoabieu->buoi ?? '') == 'Sáng' ? 'selected' : '' }}>
                                Sáng
                            </option>
                            <option value="Chiều" {{ old('buoi', $thoikhoabieu->buoi ?? '') == 'Chiều' ? 'selected' : '' }}>
                                Chiều
                            </option>
                            <option value="Tối" {{ old('buoi', $thoikhoabieu->buoi ?? '') == 'Tối' ? 'selected' : '' }}>
                                Tối
                            </option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="ngay" class="form-label">Ngày</label>
                        <input name="ngay" id="ngay" type="date" value="{{ $thoikhoabieu->ngay }}" class="form-control" placeholder="Chọn ngày">
                    </div>  

                    <div class="mt-3">
                        <label for="tietdau" class="form-label">Tiết đầu</label>
                        <select id="tietdau" name="tietdau" value="{{ $thoikhoabieu->tietdau }}" class="form-select mt-2">
                            <!-- Các tùy chọn tiết học sẽ được cập nhật động -->
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="tietcuoi" class="form-label">Tiết cuối</label>
                        <select id="tietcuoi" name="tietcuoi" value="{{ $thoikhoabieu->tietcuoi }}" class="form-select mt-2">
                            <!-- Các tùy chọn tiết học sẽ được cập nhật động -->
                        </select>
                    </div>
                    <script>
                        // Lấy các phần tử buổi và tiết
                        const buoiSelect = document.getElementById('buoi');
                        const tietdauSelect = document.getElementById('tietdau');
                        const tietcuoiSelect = document.getElementById('tietcuoi');
                        
                        // Dữ liệu tiết học cho từng buổi
                        const tietHocOptions = {
                            'Sáng': [1, 2, 3, 4],
                            'Chiều': [7, 8, 9, 10],
                            'Tối': [13, 14, 15, 16] // Cho phép các tiết lớn hơn 10
                        };
                        
                        // Hàm cập nhật tiết học
                        function updateTietHoc() {
                            // Lấy giá trị buổi đã chọn
                            const selectedBuoi = buoiSelect.value;
                        
                            // Xóa các tùy chọn cũ
                            tietdauSelect.innerHTML = '';
                            tietcuoiSelect.innerHTML = '';
                        
                            // Thêm các tùy chọn mới cho tiết đầu và tiết cuối
                            if (tietHocOptions[selectedBuoi]) {
                                tietHocOptions[selectedBuoi].forEach(tiet => {
                                    const option = document.createElement('option');
                                    option.value = tiet;
                                    option.textContent = tiet;
                                    tietdauSelect.appendChild(option);
                        
                                    // Thêm vào tietcuoiSelect nữa nếu cần
                                    const option2 = document.createElement('option');
                                    option2.value = tiet;
                                    option2.textContent = tiet;
                                    tietcuoiSelect.appendChild(option2);
                                });
                            }
                        }
                        
                        // Gắn sự kiện thay đổi cho buổi học
                        buoiSelect.addEventListener('change', updateTietHoc);
                        
                        // Cập nhật tiết học ban đầu
                        updateTietHoc();
                        </script>       
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

        $('.dltBtn').click(function(e) {
            var url = $(this).data('url');
            var fileName = $(this).data('name');
            var resourceItem = $(this).closest('li'); // Lưu trữ phần tử 'li' chứa nút xóa
            e.preventDefault();

            Swal.fire({
                title: 'Bạn có chắc muốn xóa không?',
                text: "Bạn không thể lấy lại dữ liệu sau khi xóa: " + fileName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gửi yêu cầu AJAX để xóa tài nguyên
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Đã xóa!', 'Tệp đã được xóa thành công.', 'success');
                                resourceItem.remove(); // Loại bỏ phần tử li khỏi giao diện
                            } else {
                                Swal.fire('Lỗi!', 'Đã có lỗi xảy ra khi xóa tệp.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Đã có lỗi khi gửi yêu cầu.', 'error');
                        }
                    });
                }
            });
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