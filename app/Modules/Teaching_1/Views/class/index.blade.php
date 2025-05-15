@extends('backend.layouts.master')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách lớp học
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.class.create') }}" class="btn btn-primary shadow-md mr-2">Thêm lớp học</a>
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $classes->currentPage() }} trong {{ $classes->lastPage() }} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{ route('admin.class.search') }}" method="get">
                        <input type="text" 
                               name="datasearch" 
                               class="ipsearch form-control w-56 box pr-10" 
                               placeholder="Tìm kiếm lớp học..."
                               value="{{ isset($search) ? $search : '' }}">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">TÊN LỚP</th>
                        <th class="whitespace-nowrap">GIẢNG VIÊN</th>
                        <th class="whitespace-nowrap">NGÀNH</th>
                        <th class="whitespace-nowrap">SỐ LƯỢNG TỐI ĐA</th>
                        <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr class="intro-x">
                            <td class="text-left">{{ $class->class_name }}</td>
                            <td class="text-left">{{ $class->teacher->user->full_name ?? 'N/A' }}</td> <!-- Tên giảng viên -->
                            <td class="text-left">{{ $class->nganh->title ?? 'N/A' }}</td> <!-- Tên ngành -->
                            <td class="text-left">{{ $class->max_students }}</td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.class.edit', $class->id) }}" class="flex items-center mr-3">
                                        <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.class.destroy', $class->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a class="flex items-center text-danger dltBtn" data-id="{{ $class->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $classes->links('vendor.pagination.tailwind') }}
        </nav>
    </div>
    <!-- END: Pagination -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $('.dltBtn').click(function(e) {
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

    $(".ipsearch").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var form = $(this).closest('form');
            if ($(this).val().length > 0) {
                form.submit();
            } else {
                Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });
</script>
@endsection
