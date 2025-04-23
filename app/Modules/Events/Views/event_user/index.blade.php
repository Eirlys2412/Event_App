@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách người dùng tham gia sự kiện</h2>

<div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
    <!-- Nút thêm người dùng -->
    <a href="{{ route('admin.event_user.create', ['event_id' => request('event_id')]) }}" class="btn btn-primary shadow-md mr-2">
        Thêm người dùng
    </a>

    <!-- Form lọc + export -->
    <form method="GET" action="" id="filterForm" class="flex items-center space-x-2">
        <select name="event_id" class="form-select" onchange="submitFilter()">
            <option value="">-- Chọn sự kiện --</option>
            @foreach ($events as $event)
                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                    {{ $event->title }}
                </option>
            @endforeach
        </select>

        @if(request('event_id'))
        <button type="button" class="btn btn-outline-success flex items-center text-sm px-3 py-1.5 rounded-md shadow-sm hover:bg-green-600 hover:text-white transition-all" onclick="exportExcel()">
    <i data-lucide="file-down" class="w-4 h-4 mr-1.5"></i> Xuất Excel
</button>

        @endif
    </form>
</div>

<!-- Bảng danh sách -->
<div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-nowrap">TÊN NGƯỜI DÙNG</th>
                <th class="text-center whitespace-nowrap">SỰ KIỆN</th>
                <th class="text-center whitespace-nowrap">VAI TRÒ</th>
                <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eventUsers as $eventUser)
                <tr class="intro-x">
                    <td>{{ $eventUser->user->full_name ?? 'Không có tên' }}</td>
                    <td class="text-center">{{ $eventUser->event->title ?? 'Không có sự kiện' }}</td>
                    <td class="text-center">{{ $eventUser->role->title ?? 'Không có vai trò' }}</td>
                    <td class="text-center">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.event_user.edit', $eventUser->id) }}" class="flex items-center mr-3">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            <form action="{{ route('admin.event_user.destroy', $eventUser->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-danger dltBtn" data-id="{{ $eventUser->id }}">
                                    <i data-lucide="trash" class="w-4 h-4 mr-1"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-slate-500">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
    <nav class="w-full sm:w-auto sm:mr-auto">
        {{ $eventUsers->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </nav>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Xử lý lọc sự kiện
    function submitFilter() {
        const form = document.getElementById('filterForm');
        form.action = "{{ route('admin.event_user.index') }}";
        form.submit();
    }

    // Xử lý xuất Excel
    function exportExcel() {
        const eventId = document.querySelector('[name="event_id"]').value;
        if (eventId) {
            window.location.href = "{{ url('admin/event-user/export') }}/" + eventId;
        }
    }

    // Cảnh báo xoá
    $('.dltBtn').click(function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Bạn có chắc muốn xóa không?',
            text: "Dữ liệu sẽ không thể phục hồi sau khi xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
