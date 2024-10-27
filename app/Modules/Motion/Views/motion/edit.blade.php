@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chỉnh sửa Motion
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.motion.update', $motion->id) }}" id="motionForm">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">
                <div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control" placeholder="Nhập tiêu đề" value="{{ old('title', $motion->title) }}" required>
                </div>

                <div class="mt-3">
                    <label for="icon" class="form-label">Icon</label>
                    <input id="icon" name="icon" type="text" class="form-control" placeholder="Nhập icon (URL hoặc emoji)" value="{{ old('icon', $motion->icon) }}" required>
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
    document.getElementById('motionForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn chặn gửi form mặc định

        Swal.fire({
            title: 'Bạn có chắc chắn muốn cập nhật motion?',
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
</script>
@endsection