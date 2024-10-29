@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm cho "{{ $searchdata }}"
    </h2>

    <!-- Thanh công cụ cho tìm kiếm -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary shadow-md mr-2">Thêm tài nguyên</a>

        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $resources->currentPage() }} trong
            {{ $resources->lastPage() }} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="{{ route('admin.resources.search') }}" method="get">
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10"
                           value="{{ $searchdata }}" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </form>
            </div>
        </div>
    </div>

    <!-- Hiển thị kết quả tìm kiếm -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        @if (isset($resources) && count($resources) > 0)
            @foreach ($resources as $resource)
                @php
                    $fileType = $resource->file_type;

                @endphp

                <div class="intro-y col-span-6 sm:col-span-4 lg:col-span-3">
                    <div class="card p-4 border rounded shadow-md relative">
                        <a href="{{ route('admin.resources.show', $resource->id) }}">
                            <div class="relative">
                                @if ($resource->link_code)
                                    @if ($resource->type_code == 'image')
                                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                             style="width: 100%; height: 10rem; object-fit: cover;" />
                                    @elseif ($resource->type_code == 'document')
                                        <a href="{{ $resource->url }}" class="text-blue-500 underline">
                                            Tải tài liệu
                                        </a>
                                    @elseif ($resource->type_code == 'video' && $resource->link_code == 'youtube')
                                        <iframe style="width: 100%; height: 10rem;"
                                                src="{{ str_replace('watch?v=', 'embed/', $resource->url) }}"
                                                frameborder="0" allowfullscreen></iframe>
                                    @endif
                                @else
                                    @switch(true)
                                        @case(strpos($fileType, 'image/') === 0)
                                            <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                                 style="width: 100%; height: 10rem; object-fit: cover;" />
                                            @break

                                        @case(strpos($fileType, 'video/') === 0)
                                            <video controls style="width: 100%; height: 10rem;">
                                                <source src="{{ $resource->url }}" type="{{ $fileType }}">
                                                Trình duyệt của bạn không hỗ trợ thẻ video.
                                            </video>
                                            @break



                                        @case(strpos($fileType, 'audio/') === 0)
                                            <audio controls style="width: 100%;">
                                                <source src="{{ $resource->url }}" type="{{ $fileType }}">
                                                Trình duyệt của bạn không hỗ trợ thẻ audio.
                                            </audio>
                                            @break

                                        @case($fileType === 'application/pdf')
                                            <embed src="{{ $resource->url }}" type="application/pdf"
                                                   style="width: 100%; height: 10rem;" />
                                            @break
                                            
                                        @default
                                            <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                                                 style="width: 100%; height: 10rem; object-fit: cover;" />
                                    @endswitch
                                @endif

                                <div class="image-title" style="display: flex; align-items: center; justify-content: space-between; position: absolute; inset-x: 0; bottom: -1.5rem; background-color: rgba(255, 255, 255, 0.8); padding: 8px; width: 100%;">
                                    <span style="display: block; flex-grow: 1; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 50px;">
                                        {{ $resource->title }}
                                    </span>
                                    <div style="display: flex; align-items: center; gap: 20px;">
                                        <a href="{{ route('admin.resources.edit', $resource->id) }}" style="color: #3b82f6;" title="Chỉnh sửa">
                                            <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <form id="delete-form-{{ $resource->id }}" action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <a class="dltBtn" data-id="{{ $resource->id }}" href="javascript:;" title="Xóa" style="color: #ef4444;">
                                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="intro-y col-span-12">
                <div class="text-center p-4">
                    <p class="text-lg text-red-600">Không tìm thấy tài nguyên nào!</p>
                </div>
            </div>
        @endif
    </div>

    {{ $resources->links() }}
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
