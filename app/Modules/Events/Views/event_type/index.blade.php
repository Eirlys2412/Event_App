@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách Loại Sự Kiện
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.event_type.create') }}" class="btn btn-primary shadow-md mr-2">Thêm loại sự kiện</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $EventTypes->currentPage() }} trong {{ $EventTypes->lastPage() }} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{ route('admin.event_type.search') }}" method="get">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="datasearch" class="ipsearch form-control box" placeholder="Tìm kiếm...">
                            <button type="submit" class="btn btn-secondary">Tìm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">ID</th>
                        <th class="whitespace-nowrap">Tiêu đề</th>
                        <!-- <th class="whitespace-nowrap">Slug</th> -->
                        <th class="whitespace-nowrap">Loại địa điểm</th>
                        <th class="whitespace-nowrap">Tên địa điểm tổ chức</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                        <th class="text-center whitespace-nowrap">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($EventTypes as $eventType)
                        <tr class="intro-x">
                            <td>{{ $eventType->id }}</td>
                            <td>{{ $eventType->title }}</td>
                            <!-- <td>{{ $eventType->slug }}</td> -->
                            <td>{{ $eventType->location_type }}</td>
                            <td>{{ $eventType->location_address }}</td>
                            <td class="text-center">
                                <input type="checkbox" 
                                       data-toggle="switchbutton" 
                                       data-onlabel="active"
                                       data-offlabel="inactive"
                                       {{ $eventType->status == 'active' ? 'checked' : '' }}
                                       data-size="sm"
                                       name="toggle"
                                       value="{{ $eventType->id }}"
                                       data-style="ios">
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.event_type.edit', $eventType->id) }}" class="flex items-center mr-3"> 
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa 
                                    </a>
                                    <form action="{{ route('admin.event_type.destroy', $eventType->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $eventType->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
    </div>
    <!-- END: HTML Table Data -->

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $EventTypes->links('vendor.pagination.tailwind') }}
        </nav>
    </div>
    <!-- END: Pagination -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/assets/vendor/js/bootstrap-switch-button.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Xử lý xóa
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

    // Xử lý thay đổi trạng thái
    $("[name='toggle']").change(function() {
        var mode = $(this).prop('checked');
        var id = $(this).val();

        $.ajax({
            url: "{{ route('admin.event_type.status') }}",
            type: "post",
            data: {
                _token: '{{ csrf_token() }}',
                mode: mode,
                id: id,
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: response.msg || 'Có lỗi xảy ra khi thay đổi trạng thái.',
                    });
                    $("[name='toggle'][value='" + id + "']").prop('checked', !mode);
                }
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi mạng!',
                    text: 'Không thể kết nối đến server, vui lòng thử lại.',
                });
                $("[name='toggle'][value='" + id + "']").prop('checked', !mode);
            }
        });
    });
</script>
@endsection
