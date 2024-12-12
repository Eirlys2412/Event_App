@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa Sự Kiện</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.event.update', $event->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="intro-y box p-5">
                <!-- Tiêu đề -->
                <div>
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Nhập tiêu đề" value="{{ old('title', $event->title) }}" required>
                    @error('title')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tóm tắt -->
                <div class="mt-3">
                    <label for="summary" class="form-label">Tóm tắt</label>
                    <textarea id="summary" name="summary" class="form-control @error('summary') is-invalid @enderror" placeholder="Nhập tóm tắt" rows="3">{{ old('summary', $event->summary) }}</textarea>
                    @error('summary')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mô tả chi tiết -->
                <div class="mt-3">
                    <label for="description" class="form-label">Mô tả chi tiết</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Nhập mô tả chi tiết" rows="5">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tài nguyên (File) -->
                <div class="mt-3">
                    <label for="resources" class="form-label">Tài nguyên (File)</label>
                    <input id="resources" name="resources[]" type="file" class="form-control @error('resources') is-invalid @enderror" multiple>
                    @error('resources')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hiển thị tài nguyên đã tải lên -->
                @if ($event->resources)
                    <div class="mt-3">
                        <strong>Tài nguyên đã tải lên:</strong>
                        <ul>
                            @foreach (json_decode($event->resources)->resource_ids as $resourceId)
                                @php
                                    $resource = \App\Modules\Resource\Models\Resource::find($resourceId);
                                @endphp
                                @if ($resource)
                                    <li>
                                        <a href="{{ asset($resource->url) }}" target="_blank">{{ $resource->file_name }}</a>
                                        <a href="javascript:;" class="btn btn-danger btn-sm dltBtn" 
                                           data-url="{{ route('admin.event.resource.destroy', ['eventId' => $event->id, 'resourceId' => $resource->id]) }}"
                                           data-name="{{ $resource->file_name }}">
                                           Xóa
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="mt-2 text-gray-600">Chưa có tài nguyên nào được tải lên.</p>
                @endif

                <!-- Thời gian bắt đầu -->
                <div class="mt-3">
                    <label for="timestart" class="form-label">Thời gian bắt đầu</label>
                    <input id="timestart" name="timestart" type="datetime-local" class="form-control @error('timestart') is-invalid @enderror" value="{{ old('timestart', $event->timestart ? \Carbon\Carbon::parse($event->timestart)->format('Y-m-d\TH:i') : '') }}" required>
                    @error('timestart')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Thời gian kết thúc -->
                <div class="mt-3">
                    <label for="timeend" class="form-label">Thời gian kết thúc</label>
                    <input id="timeend" name="timeend" type="datetime-local" class="form-control @error('timeend') is-invalid @enderror" value="{{ old('timeend', $event->timeend ? \Carbon\Carbon::parse($event->timeend)->format('Y-m-d\TH:i') : '') }}" required>
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
                            <option value="{{ $eventType->id }}" {{ old('event_type_id', $event->event_type_id) == $eventType->id ? 'selected' : '' }}>{{ $eventType->title }}</option>
                        @endforeach
                    </select>
                    @error('event_type_id')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mt-3">
                    <label for="select-tags" class="form-label">Tags</label>
                    <select id="select-tags" name="tag_ids[]" multiple placeholder="..." autocomplete="off">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $tag_ids) ? 'selected' : '' }}>{{ $tag->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nút lưu -->
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Khởi tạo Tom Select cho trường tags
new TomSelect("#select-tags", {
    create: true,
    placeholder: "Nhập các thẻ, cách nhau bởi dấu phẩy",
});

// Validate form trước khi gửi
document.querySelector('form').addEventListener('submit', function (e) {
    const title = document.getElementById('title').value;
    const timestart = document.getElementById('timestart').value;
    const timeend = document.getElementById('timeend').value;

    if (!title || !timestart || !timeend) {
        e.preventDefault(); // Ngăn form gửi nếu có trường trống
        alert('Vui lòng điền đầy đủ thông tin!');
    }
});

// Xử lý sự kiện click cho nút xóa
document.querySelectorAll('.dltBtn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định

        const url = this.getAttribute('data-url');
        const resourceName = this.getAttribute('data-name');

        if (confirm(`Bạn có chắc chắn muốn xóa tài nguyên "${resourceName}"?`)) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                if (response.ok) {
                    // Xóa phần tử khỏi danh sách
                    this.closest('li').remove();
                    alert('Tài nguyên đã được xóa thành công.');
                } else {
                    alert('Có lỗi xảy ra khi xóa tài nguyên.');
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra. Xin thử lại.');
                console.error('Error:', error);
            });
        }
    });
});
</script>
@endsection