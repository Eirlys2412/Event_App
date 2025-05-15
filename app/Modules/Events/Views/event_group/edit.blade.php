@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chỉnh sửa Event Group
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.event_group.update', $eventGroup->id) }}" id="eventGroupForm">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">
                <div>
                    <label for="group_id" class="form-label">Nhóm</label>
                    <select id="group_id" name="group_id"    required>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" 
                                {{ $group->id == old('group_id', $eventGroup->group_id) ? 'selected' : '' }}>
                                {{ $group->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="event_id" class="form-label">Sự kiện</label>
                    <select id="event_id" name="event_id" required>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" 
                                {{ $event->id == old('event_id', $eventGroup->event_id) ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
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
    document.getElementById('eventGroupForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn chặn gửi form mặc định

        Swal.fire({
            title: 'Bạn có chắc chắn muốn cập nhật Event Group?',
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

    // Khởi tạo Tom Select
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#group_id', {create: false});
        new TomSelect('#event_id', {create: false});
    });
</script>
@endsection
