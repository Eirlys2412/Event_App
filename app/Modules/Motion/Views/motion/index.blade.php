@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Danh sách Motion
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.motion.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Motion</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$motions->currentPage()}} trong {{$motions->lastPage()}} trang</div>
    </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">TÊN</th>
                    <th class="text-center whitespace-nowrap">ICON</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($motions as $item)
                <tr class="intro-x">
                    <td class="text-left">
                        <a target="_blank" href="#" class="font-medium whitespace-nowrap">{{ $item->title ?? 'Chưa có tiêu đề' }}</a>
                    </td>
                    <td class="w-40 text-center align-middle" style="height: 50px;">
                        <!-- Hiển thị ảnh icon, sử dụng ảnh mặc định nếu không có -->
                        @if (filter_var($item->icon, FILTER_VALIDATE_URL))
                            <img class="tooltip" src="{{ $item->icon }}" alt="icon" width="50" height="50" style="vertical-align: middle; display: inline-block;">
                        @else
                            <span class="text-2xl" style="line-height: 50px; display: inline-block;">{{ $item->icon }}</span> <!-- Hiển thị emoji -->
                        @endif
                    </td>
                    <td class="table-report__action text-center align-middle">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.motion.edit', $item->id) }}" class="flex items-center mr-3">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('admin.motion.destroy', $item->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <a class="flex items-center text-danger dltBtn" data-id="{{ $item->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
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
            {{ $motions->links() }}
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
