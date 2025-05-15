@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách Điểm Danh</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('diemdanh.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Điểm Danh</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $diemdanh->currentPage() }} trong {{ $diemdanh->lastPage() }} trang</div>
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Sinh Viên</th>
                    <th class="whitespace-nowrap">Học Phần</th>
                    <th class="whitespace-nowrap">Thời Gian</th>
                    <th class="text-center whitespace-nowrap">Trạng Thái</th>
                    <th class="whitespace-nowrap">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diemdanh as $item)
                <tr class="intro-x">
                    <td>{{ $item->diemdanh_id }}</td> 
                    <td>{{ $item->student->mssv ?? 'Chưa xác định' }}</td> 
                    <td>{{ $item->hocphan->title ?? 'Chưa xác định' }}</td> 
                    <td>{{ $item->time->format('d-m-Y H:i:s') }}</td>
                    <td class="text-center">
                        @if($item->trangthai == 'có mặt')
                            <span class="text-success">Có mặt</span>
                        @elseif($item->trangthai == 'vắng mặt')
                            <span class="text-danger">Vắng mặt</span>
                        @elseif($item->trangthai == 'muộn')
                            <span class="text-warning">Muộn</span>
                        @else
                            <span class="text-secondary">Chưa xác định</span>
                        @endif
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('diemdanh.edit',  $item->diemdanh_id) }}" class="btn btn-primary">Chỉnh sửa</a>
                            <form action="{{ route('diemdanh.destroy', $item->diemdanh_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<nav class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
    {{ $diemdanh->links('vendor.pagination.tailwind') }}
</nav>
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
            text: "Bạn không thể lấy lại dữ liệu sau khi xóa.",
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
