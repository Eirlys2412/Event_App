@extends('backend.layouts.master')

@section('content')

<h2 class="intro-y text-lg font-medium mt-10">Danh sách Câu hỏi</h2>

<!-- Nút thêm câu hỏi -->
<div class="mb-4">
    <a href="{{ route('admin.tuluancauhoi.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm câu hỏi
    </a>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Nội dung</th>
                    <th class="whitespace-nowrap">Học phần</th>
                    <th class="whitespace-nowrap">Người tạo</th>
                    <th class="whitespace-nowrap">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tuluancauhois as $item)
                <tr class="intro-x">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->content }}</td>
                    <td>{{ optional($item->module)->title }}</td>
                    <td>{{ optional($item->user)->name }}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="{{ route('admin.tuluancauhoi.edit', $item->id) }}" class="flex items-center mr-3">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            <form action="{{ route('admin.tuluancauhoi.destroy', $item->id) }}" method="post" class="dlt-form">
                                @csrf
                                @method('DELETE')
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
        {{ $tuluancauhois->links() }} <!-- Thêm phân trang -->
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
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
    });
</script>
@endsection