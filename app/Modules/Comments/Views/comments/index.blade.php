@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Danh sách bình luận</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.comments.create') }}?item_id={{ $itemId }}&item_code={{ $itemCode }}" class="btn btn-primary shadow-md mr-2">Thêm bình luận mới</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $comments->currentPage() }} trong {{ $comments->lastPage() }} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="relative">
                <form action="{{ route('admin.comments.search') }}" method="get" class="flex items-center">
                    <input type="hidden" name="item_id" value="{{ $itemId }}">
                    <input type="hidden" name="item_code" value="{{ $itemCode }}">
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 pl-10 pr-10 py-2 rounded-lg border-gray-300"
                        placeholder="Tìm kiếm..." autocomplete="off">
                    <button type="submit" class="absolute right-0 top-1/2 transform -translate-y-1 p-2 bg-transparent border-none cursor-pointer">
                        <i class="w-4 h-4 text-gray-500" data-lucide="search"></i>
                    </button>
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
                    <th class="whitespace-nowrap">MỤC</th> {{-- Thêm cột Mục (item_code) --}}
                    <th class="whitespace-nowrap">NỘI DUNG</th>
                    <th class="whitespace-nowrap">NGƯỜI DÙNG</th>
                    <th class="whitespace-nowrap">THỜI GIAN</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @if ($comments->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <strong>Không có bình luận nào hiển thị.</strong>
                        </td>
                    </tr>
                @else
                    @foreach($comments->unique('id') as $comment) {{-- Loại bỏ bình luận trùng lặp --}}
                        <tr class="intro-x">
                            <td class="text-left"><a href="#" class="font-medium">{{ $comment->id }}</a></td>
                            <td class="text-blue-600 font-semibold">{{ $comment->item_code }}</td> {{-- Hiển thị item_code --}}
                            <td>{{ Str::limit($comment->content, 50) }}</td>
                            <td>{{ optional($comment->user)->username ?? 'N/A' }}</td>
                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="table-report__action">
                                <a href="{{ route('admin.comments.edit', $comment->id) }}" class="mr-3">Edit</a>
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <a class="text-danger dltBtn" href="javascript:;" data-id="{{ $comment->id }}">Delete</a>
                                </form>
                            </td>
                        </tr>

                        @foreach($comment->replies->unique('id') as $reply) {{-- Loại bỏ phản hồi trùng lặp --}}
                            <tr class="intro-x bg-gray-100">
                                <td class="text-left pl-10">↳ <a href="#" class="font-medium">{{ $reply->id }}</a></td>
                                <td class="text-blue-500">{{ $reply->item_code }}</td> {{-- Hiển thị item_code của phản hồi --}}
                                <td>{{ Str::limit($reply->content, 50) }}</td>
                                <td>{{ optional($reply->user)->username ?? 'N/A' }}</td>
                                <td>{{ $reply->created_at->format('d/m/Y H:i') }}</td>
                                <td class="table-report__action">
                                    <a href="{{ route('admin.comments.edit', $reply->id) }}" class="mr-3">Edit</a>
                                    <form action="{{ route('admin.comments.destroy', $reply->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a class="text-danger dltBtn" href="javascript:;" data-id="{{ $reply->id }}">Delete</a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- END: Data List -->

<!-- BEGIN: Pagination -->
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
    <nav class="w-full sm:w-auto sm:mr-auto">
        {{ $comments->appends(['item_id' => $itemId, 'item_code' => $itemCode])->links('vendor.pagination.tailwind') }}
    </nav>
</div>
<!-- END: Pagination -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
            var data = $(this).val();
            var form = $(this).closest('form');
            if (data.length > 0) {
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
