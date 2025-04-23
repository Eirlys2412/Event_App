@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách điểm danh
    </h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.event_attendance.create') }}" class="btn btn-primary shadow-md mr-2">Thêm điểm danh</a>
        <button onclick="openQrModal()" class="btn btn-success shadow-md mr-2">Tạo mã QR</button>
        <div class="hidden md:block mx-auto text-slate-500">
            Hiển thị trang {{ $attendances->currentPage() }} trong {{ $attendances->lastPage() }} trang
        </div>
    </div>

    {{-- Modal chọn sự kiện --}}
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Chọn sự kiện</h3>
            <select id="eventSelect" class="w-full border border-gray-300 rounded px-3 py-2 mb-4">
                @foreach ($events as $event)
                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                @endforeach
            </select>
            <div class="flex justify-end space-x-2">
                <button onclick="closeQrModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Đóng</button>
                <button onclick="generateQrCode()" class="btn btn-primary shadow-md">Tạo mã QR</button>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2 table-auto">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">SỰ KIỆN</th>
                    <th class="text-center whitespace-nowrap">NGƯỜI DÙNG</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">THỜI GIAN ĐIỂM DANH</th>
                    <th class="text-center whitespace-nowrap">VỊ TRÍ</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="intro-x">
                        <td class="text-left break-words">{{ $attendance->event->title ?? '-' }}</td>
                        <td class="text-center">{{ $attendance->user->full_name ?? '-' }}</td>
                        <td class="text-center">
                            @if($attendance->status === 'active')
                                <span class="bg-green-100 text-green-700 px-2 py-1 text-xs rounded">Đã điểm danh</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs rounded">Chưa điểm danh</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $attendance->checked_in_at ? $attendance->checked_in_at->format('d/m/Y H:i:s') : '-' }}
                        </td>
                        <td class="text-center">{{ $attendance->check_in_location ?? '-' }}</td>
                        <td class="table-report__action text-center">
                            <div class="flex flex-col justify-center items-center space-y-2">
                                <a href="{{ route('admin.event_attendance.edit', $attendance->id) }}" class="flex items-center text-primary">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.event_attendance.destroy', $attendance->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $attendance->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                                    </a>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Không có dữ liệu điểm danh.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-5">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $attendances->links('vendor.pagination.tailwind') }}
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

        function openQrModal() {
            document.getElementById('qrModal').classList.remove('hidden');
            document.getElementById('qrModal').classList.add('flex');
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.remove('flex');
            document.getElementById('qrModal').classList.add('hidden');
        }

        function generateQrCode() {
            const eventId = document.getElementById('eventSelect').value;
            if (!eventId) {
                Swal.fire('Thông báo', 'Vui lòng chọn sự kiện.', 'info');
                return;
            }
            window.location.href = `/admin/eventattendance/generate-qr/${eventId}`;
        }
    </script>
@endsection
