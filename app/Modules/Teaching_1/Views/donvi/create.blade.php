@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Đơn vị
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.donvi.store') }}">
    @csrf
    <div class="intro-y box p-5">
        <div>
            <label for="title" class="form-label">Tên Đơn vị</label>
            <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tên đơn vị" required>
        </div>

        <!-- Đơn vị Cha -->
        <div class="mt-3">
            <label for="parent_id" class="form-label">Đơn vị Cha</label>
            <select id="parent_id" name="parent_id" class="form-control" placeholder="Chọn đơn vị cha">
                <option value="">Không có</option> <!-- Tùy chọn không có đơn vị cha -->
                @foreach($donviList as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
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
    document.querySelector('form').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value;

        if (!title) {
            e.preventDefault(); // Ngăn form gửi nếu có trường trống
            alert('Vui lòng điền đầy đủ thông tin!');
        } else {
            // Tạo slug tự động từ title
            const slug = document.getElementById('title').value.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            // Chèn slug vào trường ẩn hoặc thay thế slug nếu có
            document.getElementById('slug').value = slug;
        }
    });
</script>
@endsection
