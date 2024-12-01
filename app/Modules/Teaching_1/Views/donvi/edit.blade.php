@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chỉnh sửa Đơn vị
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.donvi.update', $donvi->id) }}" id="donviForm">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tên Đơn vị</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tên đơn vị" value="{{ old('title', $donvi->title) }}" required>
                </div>

                <div class="mt-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input id="slug" name="slug" type="text" class="form-control" placeholder="Nhập slug" value="{{ old('slug', $donvi->slug) }}" required>
                </div>

                <div class="mt-3">
                    <label for="parent_id" class="form-label">Đơn vị Cha</label>
                    <select id="parent_id" name="parent_id" class="form-control">
                        <option value="">Không có</option>
                        @foreach($donviList as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $donvi->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>



                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('donviForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn chặn gửi form mặc định

        Swal.fire({
            title: 'Bạn có chắc chắn muốn cập nhật đơn vị?',
            text: "Hãy chắc chắn rằng tất cả thông tin là chính xác.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, cập nhật!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Gửi form nếu xác nhận
            }
        });
    });
    document.getElementById('title').addEventListener('input', function() {
        let title = this.value;
        let slug = title.toLowerCase()
            .replace(/ /g, '-')            // Đổi khoảng trắng thành dấu gạch ngang
            .replace(/[^\w-]+/g, '');      // Loại bỏ các ký tự đặc biệt

        document.getElementById('slug').value = slug;
    });
</script>
@endsection
