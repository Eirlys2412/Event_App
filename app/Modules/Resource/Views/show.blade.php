@extends('backend.layouts.master')

@section('content')
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Chi tiết tài nguyên</h1>

        <h3 class="text-xl font-semibold mb-2">{{ $resource->title }}</h3>

        <div class="mb-4">
            @if (strpos($resource->file_type, 'image/') === 0 && !$resource->image_url)
                <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="max-w-full h-auto rounded shadow-lg" />
            @elseif (strpos($resource->file_type, 'video/') === 0)
                <video controls class="max-w-full h-auto rounded shadow-lg">
                    <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                    Trình duyệt của bạn không hỗ trợ video.
                </video>
            @elseif (strpos($resource->file_type, 'audio/') === 0)
                <audio controls class="max-w-full h-auto rounded shadow-lg">
                    <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                    Trình duyệt của bạn không hỗ trợ audio.
                </audio>
            @elseif ($resource->file_type === 'application/pdf')
                <img src="link_to_default_pdf_image.png" alt="Hình ảnh tài liệu PDF"
                    class="max-w-full h-auto rounded shadow-lg" />
            @elseif ($resource->youtube_url)
                <iframe class="w-full h-screen rounded shadow-lg"
                    src="{{ str_replace('watch?v=', 'embed/', $resource->youtube_url) }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            @elseif ($resource->image_url)
                <img src="{{ $resource->image_url }}" alt="Hình ảnh từ link" class="max-w-full h-auto rounded shadow-lg" />
            @else
                <p class="text-red-600">Không thể hiển thị tài liệu này.</p>
            @endif

            @if ($resource->url)
                <p class="font-medium mt-2">
                    URL:
                    <a href="{{ $resource->url }}" target="_blank"
                        class="font-normal text-blue-500 underline">{{ $resource->url }}</a>
                </p>
            @endif


            @if ($resource->description)
                <p class="font-medium mt-2">Mô tả: <span
                        class="font-normal">{{ strip_tags($resource->description) }}</span></p>
            @endif

            @if ($resource->document_url || $resource->image_url || $resource->youtube_url)
                <p class="font-medium mt-2">Liên kết tài nguyên:</p>
                <ul class="list-disc ml-5">
                    @if ($resource->document_url)
                        <li>
                            <a href="{{ $resource->document_url }}" target="_blank"
                                class="text-blue-500 underline">{{ $resource->document_url }}</a>
                        </li>
                    @endif
                    @if ($resource->youtube_url)
                        <li>
                            <a href="{{ $resource->youtube_url }}" target="_blank"
                                class="text-blue-500 underline">{{ $resource->youtube_url }}</a>
                        </li>
                    @endif
                    @if ($resource->image_url)
                        <li>
                            <a href="{{ $resource->image_url }}" target="_blank" class="text-blue-500 underline">URL Hình
                                ảnh: {{ $resource->image_url }}</a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>

        <div class="mb-4">
            <p class="font-medium">File type: <span class="font-normal">{{ $resource->file_type }}</span></p>
            <p class="font-medium">File size: <span class="font-normal">{{ $resource->file_size }} bytes</span></p>
            
            <p class="font-medium">Tags: <span class="font-normal">{{ $resource->tags }}</span></p>
        </div>

        <div class="flex space-x-2">
            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="flex items-center">
                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                Chỉnh sửa
            </a>

            <form id="delete-form-{{ $resource->id }}" action="{{ route('admin.resources.destroy', $resource->id) }}"
                method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <a class="flex items-center text-danger dltBtn" data-id="{{ $resource->id }}" href="javascript:;">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                    Xóa
                </a>
            </form>

            <a href="{{ route('admin.resources.index') }}" class="flex items-center text-secondary">
                <i data-lucide="arrow-left-circle" class="w-4 h-4 mr-1"></i>
                Quay lại danh sách
            </a>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.dltBtn').click(function(e) {
            var resourceId = $(this).data('id');
            var form = $('#delete-form-' + resourceId);
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc muốn xóa không?',
                text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire(
                        'Đã xóa!',
                        'Tài nguyên của bạn đã được xóa.',
                        'success'
                    );
                }
            });
        });
    </script>
@endsection
