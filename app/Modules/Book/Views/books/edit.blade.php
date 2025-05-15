@extends('backend.layouts.master')

@section('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh sửa sách
    </h2>

    <div class="mt-5">
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mt-3">
                <label>Tiêu đề</label>
                <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
            </div>

            <div class="mt-3">
                <label>Ảnh hiện tại</label>
                @if ($book->photo)
                    <img src="{{ $book->photo }}" alt="{{ $book->title }}" style="width: 100px; height: auto;">
                @endif
                <input type="file" name="photo" class="form-control mt-2" accept="image/*">
            </div>

            <div class="mt-3">
                <label for="book_type_id" class="form-label">Loại sách</label>
                <select name="book_type_id" id="book_type_id" class="form-control" required>
                    <option value="">Chọn loại sách</option>
                    @foreach ($bookTypes as $bookType)
                        <option value="{{ $bookType->id }}"
                            {{ old('book_type_id', $book->book_type_id) == $bookType->id ? 'selected' : '' }}>
                            {{ $bookType->title }}
                        </option>
                    @endforeach
                </select>
                @error('book_type_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-3">
                <label>Tóm tắt</label>
                <textarea name="summary" class="form-control">{{ $book->summary }}</textarea>
            </div>

            <div class="mt-3">
                <label>Nội dung</label>
                <textarea name="content" class="form-control">{{ $book->content }}</textarea>
            </div>

            <div class="mt-3">
                <label for="document" class="form-label">Tệp phương tiện</label>
                <input type="file" name="document[]" class="form-control" multiple>
                @if ($resources && count($resources) > 0)
                    <div class="mt-3">
                        <strong>Tệp đã tải lên:</strong>
                        <ul>
                            @foreach ($resources as $resource)
                                <li>
                                    <a href="{{ asset($resource->url) }}" target="_blank">
                                        {{ $resource->file_name }}
                                    </a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm dltBtn"
                                        data-url="{{ route('admin.books.removeResource', ['bookId' => $book->id, 'resourceId' => $resource->id]) }}"
                                        data-name="{{ $resource->file_name }}">
                                        Xóa
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="mt-2 text-gray-600">Chưa có tệp nào được chọn.</p>
                @endif
            </div>

            <div class="mt-3">
                <label style="min-width:70px" class="form-select-label" for="status">Tình trạng</label>
                <select name="status" class="form-select mt-2 sm:mr-2">
                    <option value="active" {{ $book->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $book->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="mt-3">
                <label for="post-form-4" class="form-label">Tags</label>
                <select id="select-junk" name="tag_ids[]" multiple placeholder=" ..." autocomplete="off">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, $tag_ids) ? 'selected' : '' }}>
                            {{ $tag->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var select = new TomSelect('#select-junk', {
            maxItems: null,
            allowEmptyOption: true,
            plugins: ['remove_button'],
            sortField: {
                field: "text",
                direction: "asc"
            },
            onItemAdd: function() {
                this.setTextboxValue('');
                this.refreshOptions();
            },
            create: true
        });
        @if (count($tag_ids) == 0)
            select.clear();
        @endif

        $('.dltBtn').click(function(e) {
            var url = $(this).data('url');
            var fileName = $(this).data('name');
            var resourceItem = $(this).closest('li'); // Lưu trữ phần tử 'li' chứa nút xóa
            e.preventDefault();

            Swal.fire({
                title: 'Bạn có chắc muốn xóa không?',
                text: "Bạn không thể lấy lại dữ liệu sau khi xóa: " + fileName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Gửi yêu cầu AJAX để xóa tài nguyên
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Đã xóa!', 'Tệp đã được xóa thành công.', 'success');
                                resourceItem.remove(); // Loại bỏ phần tử li khỏi giao diện
                            } else {
                                Swal.fire('Lỗi!', 'Đã có lỗi xảy ra khi xóa tệp.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Đã có lỗi khi gửi yêu cầu.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endsection
