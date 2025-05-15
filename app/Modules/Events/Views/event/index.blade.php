@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Tất cả sự kiện
    </h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.event.create') }}" class="btn btn-primary shadow-md mr-2">Thêm sự kiện</a>
        <div class="hidden md:block mx-auto text-slate-500">
            Hiển thị trang {{ $eventList->currentPage() }} trong {{ $eventList->lastPage() }} trang
        </div>
    </div>

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2 table-auto">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">TÊN SỰ KIỆN</th>
                    <th class="text-center whitespace-nowrap">THỜI GIAN BẮT ĐẦU</th>
                    <th class="text-center whitespace-nowrap">THỜI GIAN KẾT THÚC</th>
                    <!-- <th class="text-center whitespace-nowrap">TÓM TẮT</th> -->
                    <!-- <th class="text-center whitespace-nowrap">MÔ TẢ</th> -->
                    <th class="text-center whitespace-nowrap">URL</th>
                    <th class="text-center whitespace-nowrap">LOẠI SỰ KIỆN</th>
                    <!-- <th class="text-center whitespace-nowrap">NGƯỜI THAM GIA</th> -->
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventList as $event)
                    <tr class="intro-x">
                        <td class="text-left break-words">
                            <a target="_blank" href="#" class="font-medium">{{ $event->title ?? 'Chưa có tiêu đề' }}</a>
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($event->timestart)->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($event->timeend)->format('d/m/Y H:i') }}
                        </td>
                        <!-- <td class="text-center">
                            {{ $event->summary ?? 'Chưa có tóm tắt' }}
                        </td>
                        <td class="text-center">
                            {{ $event->description ?? 'Chưa có mô tả' }}
                        </td> -->
                        <td class="text-center">
    @if (!empty($event->resource_data) && count($event->resource_data))
        @foreach ($event->resource_data as $res)
            <div class="mb-2">
                @if (Str::contains($res->file_type, 'image'))
                    <img src="{{ asset($res->url) }}" alt="{{ $res->title }}" width="100" class="rounded shadow">
                @elseif (Str::contains($res->file_type, 'video'))
                    <video width="160" height="90" controls>
                        <source src="{{ asset($res->url) }}" type="{{ $res->file_type }}">
                        Trình duyệt của bạn không hỗ trợ video.
                    </video>
                @elseif (Str::contains($res->file_type, 'audio'))
                    <audio controls>
                        <source src="{{ asset($res->url) }}" type="{{ $res->file_type }}">
                        Trình duyệt không hỗ trợ audio.
                    </audio>
                @else
                    <a href="{{ asset($res->url) }}" target="_blank" class="text-blue-500 hover:underline">
                        📄 {{ $res->title ?? 'Tài liệu' }}
                    </a>
                @endif
            </div>
        @endforeach
    @else
        <span class="text-gray-400 italic">Không có tài nguyên</span>
    @endif
</td>

                        <td class="text-center">
                            {{ $event->eventType->title ?? 'Chưa có loại sự kiện' }}
                        </td>
                        <!-- <td class="text-center">
                            @php
                                $userIds = is_string($event->user_ids) ? json_decode($event->user_ids, true) : (is_array($event->user_ids) ? $event->user_ids : []);
                                $users = \App\Models\User::whereIn('id', $userIds)->pluck('full_name')->toArray();
                            @endphp
                            {{ !empty($users) ? implode(', ', $users) : 'Chưa có người tham gia' }}
                        </td> -->
                        <td class="table-report__action text-center">
                            <div class="flex flex-col justify-center items-center space-y-2">
                                <a href="{{ route('admin.event.edit', $event->id) }}" class="flex items-center">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.event.destroy', $event->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $event->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                                    </a>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $eventList->links('vendor.pagination.tailwind') }}
        </nav>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
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
                }
            });
        });
    </script>
@endsection
