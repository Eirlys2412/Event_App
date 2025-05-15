@extends('backend.layouts.master')

@section ('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Motion
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.motion.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề" required>
                </div>
                
                <div class="mt-3">
                    <label for="icon" class="form-label">Icon (URL hoặc Emoji)</label>
                    <input id="icon" name="icon" type="text" class="form-control" placeholder="Nhập URL hoặc emoji" required>
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
    // Ví dụ: Khởi tạo Dropzone nếu bạn muốn giữ lại chức năng upload hình ảnh
    Dropzone.options.mydropzone = {
        paramName: "file", // Tên của file
        maxFilesize: 2, // Kích thước tối đa (MB)
        acceptedFiles: ".jpeg,.jpg,.png,.gif", // Các loại file được chấp nhận
        success: function (file, response) {
            // Xử lý khi upload thành công
            document.getElementById('icon').value = response.filePath; // Giả sử response chứa đường dẫn file
        },
        error: function (file, response) {
            // Xử lý khi có lỗi
            alert("Có lỗi xảy ra: " + response);
        }
    };

    // Nếu cần xử lý thêm gì khác
    // Ví dụ: Validate nhập URL hoặc emoji
    document.querySelector('form').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value;
        const icon = document.getElementById('icon').value;

        if (!title || !icon) {
            e.preventDefault(); // Ngăn form gửi nếu có trường trống
            alert('Vui lòng điền đầy đủ thông tin!');
        }
    });
</script>
@endsection