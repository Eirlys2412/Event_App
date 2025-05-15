@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Người Dùng Tham Gia Sự Kiện
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.event_user.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <!-- Chọn sự kiện -->
                <div>
                    <label for="event_id" class="form-label">Chọn sự kiện</label>
                    <select id="event_id" name="event_id" class="form-control @error('event_id') is-invalid @enderror" required>
                        <option value="">Chọn sự kiện</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Chọn người dùng -->
                <div class="mt-3">
                    <label for="user_id" class="form-label">Người tham gia</label>
                    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">Chọn người tham gia</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{  $user->full_name}}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Chọn vai trò -->
                <div class="mt-3">
                    <label for="role_id" class="form-label">Vai trò</label>
                    <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                        <option value="">Chọn vai trò</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->title }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Nút lưu -->
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
    // Khởi tạo Tom Select cho danh sách người dùng
    var selectUser = new TomSelect('#user_full_name', {
        create: false,
        placeholder: "Chọn người tham gia...",
        sortField: { field: "text", direction: "asc" }
    });
</script>
@endsection
