@extends('backend.layouts.master')

@section('content')
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Chi tiết tài nguyên</h1>

        <h3 class="text-xl font-semibold mb-2">{{ $resource->title }}</h3>

        <div class="mb-4">
            
            @if($resource->link_code )
                @if($resource->type_code == 'image')
                    <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                    style="width: 100%; height: 500px; object-fit: cover;" />
                @endif
                @if ($resource->type_code == 'document')
                    <a href="{{$resource->url }}" class="text-blue-500 underline">Tải tài liệu</a>
                @endif
                @if ($resource->type_code == 'video' && $resource->link_code=='youtube')
                    <iframe style="width: 100%; height: 500px;" src="{{ str_replace('watch?v=', 'embed/', $resource->url) }}"
                    frameborder="0" allowfullscreen></iframe>
                @endif


            @else

                @switch(true)
                    @case(strpos( $resource->file_type, 'image/') === 0)

                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                            style="width: 100%; height: 500px; object-fit: cover;" />
                    @break

                    @case(strpos($resource->file_type, 'video/') === 0)
                        <video controls style="width: 100%; height: 500px;">
                            <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                            Trình duyệt của bạn không hỗ trợ thẻ video.
                        </video>
                    @break

                    @case(strpos($resource->file_type, 'audio/') === 0)
                        <audio controls style="width: 100%;">
                            <source src="{{ $resource->url }}" type="{{ $resource->file_type }}">
                            Trình duyệt của bạn không hỗ trợ thẻ audio.
                        </audio>
                    @break

                    @case($resource->file_type === 'application/pdf')
                        <embed src="{{ $resource->url }}" type="application/pdf"
                            style="width: 100%; height: 500px;" />
                    @break

                    @case(in_array($resource->file_type, [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ]))
                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                            style="width: 100%; height: 500px; object-fit: cover;" />
                    @break

                    @case(in_array($resource->file_type, [
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        ]))
                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                            style="width: 100%; height: 500px; object-fit: cover;" />
                    @break

                    @case(in_array($resource->file_type, [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]))
                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                            style="width: 100%; height: 500px; object-fit: cover;" />
                    @break

                    @default
                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                            style="width: 100%; height: 500px; object-fit: cover;" />
                @endswitch
            @endif


            
        </div>



        <div class="mb-4">
            <p class="font-medium">File type: <span class="font-normal">{{ $resource->file_type }}</span></p>
            <p class="font-medium">File size: <span class="font-normal">{{ $resource->file_size }} bytes</span></p>
            <p class="font-medium"> Tags:
                <span class="font-normal">
                    @foreach ($tag_ids as $item)
                        {{ \App\Models\Tag::find($item->tag_id)->title }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span>
            </p>
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
