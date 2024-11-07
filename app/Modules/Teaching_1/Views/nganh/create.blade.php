@extends('backend.layouts.master')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thêm ngành</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.nganh.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tên ngành</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Tên ngành" required oninput="generateSlug()">
                </div>
                <div class="mt-3">
                    <label for="code" class="form-label">Mã ngành</label>
                    <input id="code" name="code" type="text" class="form-control" placeholder="Mã ngành" required>
                </div>
                <div class="mt-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input id="slug" name="slug" type="text" class="form-control" placeholder="Slug" readonly>
                </div>
                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea id="content" name="content" class="form-control" placeholder="Nội dung" required></textarea>
                </div>
                <div class="mt-3">
                    <label for="status" class="form-select-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mt-3">
                    <label for="donvi_id" class="form-label">Đơn vị ID</label>
                    <select name="donvi_id" class="form-select mt-2" required>
                        @foreach($donvis as $donvi)
                            <option value="{{ $donvi->id }}">{{ $donvi->title }}</option>
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

<script>
    function generateSlug() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const slug = titleInput.value
            .trim() // Xóa khoảng trắng ở đầu và cuối
            .toLowerCase() // Chuyển đổi thành chữ thường
            .replace(/[^a-z0-9 -]/g, '') // Loại bỏ ký tự không hợp lệ
            .replace(/\s+/g, '-') // Thay thế khoảng trắng bằng dấu gạch ngang
            .replace(/-+/g, '-'); // Thay thế nhiều dấu gạch ngang liên tiếp bằng một dấu

        slugInput.value = slug; // Cập nhật giá trị slug
    }
</script>

@endsection