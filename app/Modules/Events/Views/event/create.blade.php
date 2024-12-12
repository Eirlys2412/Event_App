@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Sự Kiện
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <!-- Tiêu đề -->
                <div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Nhập tiêu đề" required>
                    @error('title')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug
                <div class="mt-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" placeholder="Slug tự động tạo" required readonly>
                    @error('slug')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div> -->

                <!-- Tóm tắt -->
                <div class="mt-3">
                    <label for="summary" class="form-label">Tóm tắt</label>
                    <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" placeholder="Nhập tóm tắt" rows="3"></textarea>
                    @error('summary')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mô tả chi tiết -->
                <div class="mt-3">
                    <label for="description" class="form-label">Mô tả chi tiết</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Nhập mô tả chi tiết" rows="5"></textarea>
                    @error('description')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tài nguyên (File) -->
                <div class="mt-3">
    <label for="documents" class="form-label">Tài liệu (File)</label>
    <input id="documents" name="documents[]" type="file" class="form-control @error('documents') is-invalid @enderror" multiple>
    @error('documents')
        <div class="text-red-500 text-sm">{{ $message }}</div>
    @enderror
</div>

                <!-- Thời gian bắt đầu -->
                <div class="mt-3">
                    <label for="timestart" class="form-label">Thời gian bắt đầu</label>
                    <input id="timestart" name="timestart" type="datetime-local" class="form-control @error('timestart') is-invalid @enderror" required>
                    @error('timestart')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Thời gian kết thúc -->
                <div class="mt-3">
                    <label for="timeend" class="form-label">Thời gian kết thúc</label>
                    <input id="timeend" name="timeend" type="datetime-local" class="form-control @error('timeend') is-invalid @enderror" required>
                    @error('timeend')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Loại sự kiện -->
                <div class="mt-3">
                    <label for="event_type_id" class="form-label">Loại sự kiện</label>
                    <select id="event_type_id" name="event_type_id" class="form-control @error('event_type_id') is-invalid @enderror" required>
                        <option value="">Chọn loại sự kiện</option>
                        @foreach($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}">{{ $eventType->title }}</option>
                        @endforeach
                    </select>
                    @error('event_type_id')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mt-3">
                    <label for="select-tags" class="form-label">Tags</label>
                    <select id="select-tags" name="tag_ids[]" multiple placeholder="Chọn tags..." autocomplete="off">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                        @endforeach
                    </select>
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
    // Tạo slug tự động từ title khi gửi biểu mẫu
    document.querySelector('form').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value;
        const timestart = document.getElementById('timestart').value;
        const timeend = document.getElementById('timeend').value;

        if (!title || !timestart || !timeend) {
            e.preventDefault(); // Ngăn form gửi nếu có trường trống
            alert('Vui lòng điền đầy đủ thông tin!');
        } else {
            // Tạo slug tự động từ title
            const slug = title.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            
            console.log('Generated slug:', slug);
        }
    });

    // Khởi tạo Tom Select cho trường tags
    var selectTags = new TomSelect('#select-tags', {
        create: true,
        placeholder: "Chọn tags...",
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
</script>
@endsection