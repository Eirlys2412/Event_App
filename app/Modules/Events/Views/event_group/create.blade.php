@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Event Group
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.event_group.store') }}" id="eventGroupForm">
            @csrf
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="group_id" class="form-label">Nhóm</label>
                    <select id="group_id" name="group_id" required>
                        <option value="">Chọn nhóm</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @if(old('group_id') == $group->id) selected @endif>{{ $group->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="event_id" class="form-label">Sự kiện</label>
                    <select id="event_id" name="event_id" required>
                        <option value="">Chọn sự kiện</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @if(old('event_id') == $event->id) selected @endif>{{ $event->title }}</option>
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
    // Khởi tạo Tom Select cho các dropdown
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#group_id', {
            create: false, // Không cho phép nhập tay
            onDropdownOpen: function() {
                this.settings.create = false; // Đảm bảo không cho phép nhập tay
            }
        });

        new TomSelect('#event_id', {
            create: false, // Không cho phép nhập tay
            onDropdownOpen: function() {
                this.settings.create = false; // Đảm bảo không cho phép nhập tay
            }
        });
    });

    // Validation đơn giản
    document.getElementById('eventGroupForm').addEventListener('submit', function (e) {
        const groupId = document.getElementById('group_id').value;
        const eventId = document.getElementById('event_id').value;

        if (!groupId || !eventId) {
            e.preventDefault(); // Ngăn form gửi nếu có trường trống
            alert('Vui lòng chọn nhóm và sự kiện!');
        }
    });
</script>
@endsection
