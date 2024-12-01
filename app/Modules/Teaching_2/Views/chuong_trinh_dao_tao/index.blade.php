@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách Chương trình Đào tạo
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.chuong_trinh_dao_tao.create') }}" class="btn btn-primary shadow-md mr-2">Thêm chương trình đào tạo</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$programs->currentPage()}} trong {{$programs->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="{{ route('admin.chuong_trinh_dao_tao.search') }}" method="get">
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
                        <th class="whitespace-nowrap">Tổng Tín Chỉ</th>
                        <th class="whitespace-nowrap">Ngành</th>
                        <th class="text-center whitespace-nowrap">Trạng Thái</th>
                        <th class="text-center whitespace-nowrap">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                        <tr class="intro-x">
                            <td>{{ $program->id }}</td>
                            <td>{{ $program->title }}</td>
                            <td>{{ $program->tong_tin_chi }}</td>
                            <td>{{ $program->nganh ? $program->nganh->title : 'Chưa xác định' }}</td> <!-- Hiển thị tên ngành -->
                            <td class="text-center">
                                <input type="checkbox" 
                                       data-toggle="switchbutton" 
                                       data-onlabel="active"
                                       data-offlabel="inactive"
                                       {{ $program->status == "active" ? "checked" : "" }}
                                       data-size="sm"
                                       name="toggle"
                                       value="{{ $program->id }}"
                                       data-style="ios">
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.chuong_trinh_dao_tao.edit', $program->id) }}" class="flex items-center mr-3"> 
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                                    </a>
                                    <form action="{{ route('admin.chuong_trinh_dao_tao.destroy', $program->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $program->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete 
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
            {{ $programs->links('vendor.pagination.tailwind') }}
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

    // Handle toggle change for status
    $("[name='toggle']").change(function() {
        var mode = $(this).prop('checked');
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.chuong_trinh_dao_tao.status') }}",
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
