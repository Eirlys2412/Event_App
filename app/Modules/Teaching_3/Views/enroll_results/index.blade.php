@extends('backend.layouts.master')
@section('content')

<h2 class="intro-y text-lg font-medium mt-10">
    Danh sách Kết Quả Khóa Học
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.enroll_results.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Kết Quả</a>

        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $enrollResult->currentPage() }} trong {{ $enrollResult->lastPage() }} trang</div>

        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                {{-- <form action="{{ route('admin.enroll_results.search') }}" method="get">
                    @csrf
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </form> --}}
            </div>
        </div>
    </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Sinh viên</th>
                    <th class="whitespace-nowrap">Hình Thức Thi</th>
                    <th class="whitespace-nowrap">Điểm</th>
                    <th class="text-center whitespace-nowrap">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollResult as $item)
                <tr class="intro-x">
                    <td>{{ $item->id }}</td>
                    <td>{{ $userList[$item->user_id] ?? 'N/A' }}</td>
                    <td>{{ $hocthucthi[$item->hinhthucthi_id] ?? 'N/A' }}</td>
                    <td>{{ $item->grade ?? 'Chưa có' }}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.enroll_results.edit', $item->id) }}" class="flex items-center mr-3"> 
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa 
                            </a>
                            <form action="{{ route('admin.enroll_results.destroy', $item->id) }}" method="post">
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
    </div>
    <!-- END: Data List -->

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $enrollResult->links('vendor.pagination.tailwind') }}
        </nav>
    </div>
    <!-- END: Pagination -->
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
