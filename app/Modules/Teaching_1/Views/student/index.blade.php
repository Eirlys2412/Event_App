@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách Sinh Viên</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('student.create') }}" class="btn btn-primary shadow-md mr-2">Thêm Sinh Viên</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$students->currentPage()}} trong {{$students->lastPage()}} trang</div>
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Mã số sinh viên</th>
                    <th class="whitespace-nowrap">Khóa</th>
                    <th class="whitespace-nowrap">Đơn vị</th>
                    <th class="whitespace-nowrap">Ngành</th>
                    <th class="whitespace-nowrap">User ID</th> <!-- Thêm cột User ID -->
                    <th class="text-center whitespace-nowrap">Trạng thái</th> <!-- Cập nhật cột Trạng thái -->
                    <th class="whitespace-nowrap">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr class="intro-x">
                    <td>{{ $student->id }}</td>
                    <td><a target="_blank" href="#" class="font-medium whitespace-nowrap">{{ $student->mssv }}</a></td>
                    <td>{{ $student->khoa }}</td>
                    <td>{{ $student->donvi->title ?? 'Chưa xác định' }}</td> <!-- Hiển thị tên đơn vị -->
                    <td>{{ $student->nganh->title ?? 'Chưa xác định' }}</td> <!-- Hiển thị tên ngành -->
                    <td>{{ $student->user_id ?? 'Chưa xác định' }}</td> <!-- Hiển thị user_id -->

                    <!-- Hiển thị trạng thái dưới dạng chữ với màu chữ -->
                    <td class="text-center">
                        @if($student->status == 'đang học')
                            <span class="text-success">Đang học</span>
                        @elseif($student->status == 'thôi học')
                            <span class="text-danger">Thôi học</span>
                        @elseif($student->status == 'tốt nghiệp')
                            <span class="text-primary">Tốt nghiệp</span>
                        @else
                            <span class="text-secondary">Chưa xác định</span>
                        @endif
                    </td>

                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('student.edit', $student->id) }}" class="flex items-center mr-3">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            <form action="{{ route('student.destroy', $student->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="flex items-center text-danger dltBtn" data-id="{{ $student->id }}">
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
    {{ $students->links('vendor.pagination.tailwind') }}
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
