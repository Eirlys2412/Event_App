@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Danh sách Event
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.event.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Event</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$eventList->currentPage()}} trong {{$eventList->lastPage()}} trang</div>
    </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">TÊN SỰ KIỆN</th>
                    <th class="text-center whitespace-nowrap">THỜI GIAN</th>
                    <th class="text-center whitespace-nowrap">LOẠI SỰ KIỆN</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventList as $event)
                <tr class="intro-x">
                    <td class="text-left">
                        <a target="_blank" href="#" class="font-medium whitespace-nowrap">{{ $event->title ?? 'Chưa có tiêu đề' }}</a>
                    </td>
                    <td class="text-center">
                        @php
                            // Chuyển đổi chuỗi thời gian thành đối tượng DateTime
                            $startDate = new DateTime($event->timestart);
                            $endDate = new DateTime($event->timeend);
                            
                            // Kiểm tra xem thời gian bắt đầu và kết thúc có cùng ngày không
                            if ($startDate->format('Y-m-d') == $endDate->format('Y-m-d')) {
                                // Nếu cùng ngày, chỉ hiển thị giờ và phút
                                $eventTime = $startDate->format('d/m/Y H:i') . ' đến ' . $endDate->format('H:i');
                            } else {
                                // Nếu khác ngày, hiển thị cả ngày và giờ
                                $eventTime = $startDate->format('d/m/Y H:i') . ' đến ' . $endDate->format('d/m/Y H:i');
                            }
                        @endphp
                        {{ $eventTime }}
                    </td>
                    <td class="text-center">
                        <!-- Kiểm tra mối quan hệ eventType và hiển thị loại sự kiện -->
                        {{ $event->eventType->title ?? 'Chưa có loại sự kiện' }}
                    </td>
                    <td class="table-report__action text-center align-middle">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.event.edit', $event->id) }}" class="flex items-center mr-3">
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

        <!-- Hiển thị phân trang -->
        <div class="mt-4">
            {{ $eventList->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Xử lý sự kiện click cho nút xóa
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
                form.submit(); // Gửi form để thực hiện xóa
            }
        });
    });
</script>
@endsection
