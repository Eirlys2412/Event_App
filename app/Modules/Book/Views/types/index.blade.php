@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách loại sách</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.booktypes.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm loại sách
            </a>

            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $bookTypes->currentPage() }} trong
                {{ $bookTypes->lastPage() }} trang</div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Tên loại sách</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookTypes as $bookType)
                        <tr class="intro-x">
                            <td>
                                <a target="_blank" href=""
                                    class="font-medium whitespace-nowrap">{{ $bookType->title }}</a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" data-toggle="switchbutton" data-onlabel="active"
                                    data-offlabel="inactive" {{ $bookType->status == 'active' ? 'checked' : '' }}
                                    data-size="sm" name="toggle" value="{{ $bookType->id }}" data-style="ios">
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.booktypes.edit', $bookType->id) }}"
                                        class="flex items-center mr-3" href="javascript:;">
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.booktypes.destroy', $bookType->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $bookType->id }}"
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
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{ $bookTypes->links('vendor.pagination.tailwind') }}
            </nav>
        </div>
        <!-- END: Pagination -->
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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

            $("[name='toggle']").change(function() {
                var mode = $(this).prop('checked');
                var id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.booktypes.status') }}",
                    type: "post",
                    data: {
                        _token: '{{ csrf_token() }}',
                        mode: mode,
                        id: id,
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                });
            });
        </script>
    @endsection
