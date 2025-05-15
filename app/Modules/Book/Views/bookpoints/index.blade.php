@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách hành động điểm sách</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.bookpoints.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm hành động điểm sách
            </a>

            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $bookPoints->currentPage() }} trong
                {{ $bookPoints->lastPage() }} trang</div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">ID</th>
                        <th class="whitespace-nowrap">Hành động</th>
                        <th class="whitespace-nowrap">Điểm</th>
                        <th class="text-center whitespace-nowrap">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookPoints as $bookPoint)
                        <tr class="intro-x">
                            <td>{{ $bookPoint->id }}</td>
                            <td>{{ $bookPoint->func_cmd }}</td>
                            <td>{{ $bookPoint->point }}</td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.bookpoints.edit', $bookPoint->id) }}"
                                        class="flex items-center mr-3" href="javascript:;">
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.bookpoints.destroy', $bookPoint->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $bookPoint->id }}"
                                            href="javascript:;" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                            Xóa
                                        </a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $bookPoints->links() }}
        </div>
        <!-- END: Data List -->
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.dltBtn').click(function(e) {
            var bookId = $(this).data('id');
            var form = $(this).closest('form');
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
