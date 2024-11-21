@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm sách
    </h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary shadow-md mr-2">Thêm sách</a>
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $books->currentPage() }} trong
            {{ $books->lastPage() }} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="{{ route('admin.books.search') }}" method="get">
                    @csrf
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10"
                        placeholder="Search..." autocomplete="off">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </form>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <style>
            .title-cell {
                max-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .summary-cell {
                max-width: 1000px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        </style>

        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">TÊN</th>
                    <th class="text-center whitespace-nowrap">PHOTO</th>
                    <th class="whitespace-nowrap">NGƯỜI TẢI LÊN</th>
                    <th class="whitespace-nowrap">TÓM TẮT</th>
                    <th class="whitespace-nowrap">URL</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr class="intro-x">
                        <td class="title-cell">
                            <a href="#" target="_blank" class="font-medium whitespace-nowrap">{{ $book->title }}</a>
                        </td>

                        <td class="text-center ">
                            <img src="{{ $book->photo }}" alt="{{ $book->title }}"
                                style="width: 50px; height: auto;">
                        </td>

                        <td class="text-left">{{ $book->user ? $book->user->full_name : 'N/A' }}</td>

                        <td class="summary-cell">{{ $book->summary ?? 'N/A' }}</td>

                        <td>
                            @if (!empty($book->resource_urls))
                                @foreach ($book->resource_urls as $url)
                                    <a href="{{ asset($url) }}" target="_blank">{{ Str::limit(asset($url), 30) }}</a><br>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>

                        <td class="text-center">
                            <input type="checkbox" data-toggle="switchbutton" data-onlabel="active" data-offlabel="inactive"
                                {{ $book->status == 'active' ? 'checked' : '' }} data-size="sm" name="toggle"
                                value="{{ $book->id }}" data-style="ios">
                        </td>

                        <td class="table-report__action ">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="flex items-center mr-3"
                                    title="Chỉnh sửa">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $book->id }}"
                                        href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#delete-confirmation-modal" title="Xóa">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                    </a>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Không tìm thấy sách nào với tiêu chí tìm kiếm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $books->links('vendor.pagination.tailwind') }}
        </nav>
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

        $("[name='toggle']").change(function() {
            var mode = $(this).prop('checked');
            var id = $(this).val();
            $.ajax({
                url: "{{ route('admin.books.status') }}",
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
