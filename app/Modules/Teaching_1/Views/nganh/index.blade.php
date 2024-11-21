@extends('backend.layouts.master')
@section('content')


<h2 class="intro-y text-lg font-medium mt-10">Danh sách ngành</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.nganh.create') }}" class="btn btn-primary shadow-md mr-2">Thêm ngành</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$nganhs->currentPage()}} trong {{$nganhs->lastPage()}} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('admin.nganh.search') }}" method="get" class="relative">
                @csrf
                <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Tìm kiếm...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
            </form>
        </div>
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Tên ngành</th>
                    <th class="whitespace-nowrap">Mã ngành</th>
                    <th class="whitespace-nowrap">Nội dung</th>
                    <th class="whitespace-nowrap">Đơn vị</th>
                    <th class="text-center whitespace-nowrap">Trạng thái</th>
                    <th class="whitespace-nowrap">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nganhs as $item)
                <tr class="intro-x">
                    <td>{{ $item->id }}</td>
                    <td><a target="_blank" href="#" class="font-medium whitespace-nowrap">{{ $item->title }}</a></td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->content }}</td>
                    <td>{{ $item->donvi->title ?? 'Chưa xác định' }}</td> <!-- Hiển thị tên đơn vị -->
                    <td class="text-center">
                        <input type="checkbox" 
                               data-toggle="switchbutton" 
                               data-onlabel="active"
                               data-offlabel="inactive"
                               {{ $item->status == "active" ? "checked" : "" }}
                               data-size="sm"
                               name="toggle"
                               value="{{ $item->id }}"
                               data-style="ios">
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.nganh.edit', $item->id) }}" class="flex items-center mr-3">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            <form action="{{ route('admin.nganh.destroy', $item->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="flex items-center text-danger dltBtn" data-id="{{ $item->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                                </button>
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
    {{ $nganhs->links('vendor.pagination.tailwind') }}
</nav>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

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

    $("[name='toggle']").change(function() {
        var mode = $(this).prop('checked') ? 'true' : 'false'; // Lấy trạng thái checkbox
        var id = $(this).val(); // Lấy ID của ngành
        $.ajax({
            url: "{{ route('admin.nganh.status') }}", // Đường dẫn đến route cập nhật trạng thái
            type: "post",
            data: {
                _token: '{{ csrf_token() }}', // Token CSRF
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
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi xảy ra!',
                    text: xhr.responseText,
                });
            }
        });
    });
</script>
@endsection