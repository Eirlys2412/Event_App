@extends('backend.layouts.master')

@section ('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Sửa câu hỏi trắc nghiệm
    </h2>
</div>

<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('admin.tracnghiemcauhoi.update', $tracnghiemcauhoi->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="intro-y box p-5">
                <!-- Nội dung câu hỏi -->
                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea class="editor" name="content" id="editor2">{{ old('content', $tracnghiemcauhoi->content) }}</textarea>
                </div>

                <!-- Học phần -->
                <div class="mt-3">
                    <label for="hocphan_id" class="form-label">Học Phần</label>
                    <select name="hocphan_id" class="form-select mt-2">
                        @foreach($hocphan as $data)
                            <option value="{{ $data->id }}" {{ old('hocphan_id', $tracnghiemcauhoi->hocphan_id) == $data->id ? 'selected' : '' }}>
                                {{ $data->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Loại trắc nghiệm -->
                <div class="mt-3">
                    <label for="loai_id" class="form-label">Loại trắc nghiệm</label>
                    <select name="loai_id" class="form-select mt-2">
                        @foreach($tracnghiemloai as $data)
                            <option value="{{ $data->id }}" {{ old('loai_id', $tracnghiemcauhoi->loai_id) == $data->id ? 'selected' : '' }}>
                                {{ $data->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Người tạo -->
                <div class="mt-3">
                    <label for="user_id" class="form-label">Người tạo</label>
                    <select name="user_id" class="form-select mt-2">
                        @foreach($user as $data)
                            <option value="{{ $data->id }}" {{ old('user_id', $tracnghiemcauhoi->user_id) == $data->id ? 'selected' : '' }}>
                                {{ $data->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tags -->
                <div class="mt-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="select-junk" name="tag_ids[]" multiple placeholder="..." autocomplete="off">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" 
                                {{ in_array($tag->id, old('tag_ids', $tag_ids)) ? 'selected' : '' }}>{{ $tag->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tệp phương tiện -->
                <div class="mt-3">
                    <label for="document" class="form-label">Tệp phương tiện</label>
                    <input type="file" name="document[]" class="form-control" multiple>

                    @if ($resources && count($resources) > 0)
                        <div class="mt-3">
                            <strong>Tệp đã tải lên:</strong>
                            <ul>
                                @foreach ($resources as $resource)
                                    <li>
                                        <a href="{{ asset($resource->url) }}" target="_blank">{{ $resource->file_name }}</a>
                                        <a href="javascript:;" class="btn btn-danger btn-sm dltBtn"
                                           data-url="{{ route('admin.tracnghiemcauhoi.removeResource', ['tracnghiemcauhoiId' => $tracnghiemcauhoi->id, 'resourceId' => $resource->id]) }}"
                                           data-name="{{ $resource->file_name }}">Xóa</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="mt-2 text-gray-600">Chưa có tệp nào được chọn.</p>
                    @endif
                </div>

                <!-- Danh sách đáp án -->
                <div class="mt-3">
                    <label for="answers" class="form-label">Danh sách đáp án</label>
                    <table class="table table-bordered" id="answers-table">
                        <thead>
                            <tr>
                                <th>Đáp án</th>
                                <th>Kết quả</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($tracnghiemcauhoi->answers && count($tracnghiemcauhoi->answers) > 0)
                                @foreach($tracnghiemcauhoi->answers as $index => $answer)
                                    <tr>
                                        <td>
                                            <input type="text" name="answers[{{ $index }}][content]" class="form-control" value="{{ $answer->content }}" placeholder="Nhập đáp án">
                                        </td>
                                        <td>
                                            <select name="answers[{{ $index }}][is_correct]" class="form-select">
                                                <option value="1" {{ $answer->is_correct == 1 ? 'selected' : '' }}>Đúng</option>
                                                <option value="0" {{ $answer->is_correct == 0 ? 'selected' : '' }}>Sai</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-answer">Xóa</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">Chưa có đáp án nào được thêm vào.</td>
                                </tr>
                            @endif
                        </tbody>
                        
                    </table>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="add-answer">Thêm đáp án</button>
                </div>

                <!-- Nút lưu -->
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section ('scripts')
<script>
    var select = new TomSelect('#select-junk', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: { field: "text", direction: "asc" },
    });

    // Xử lý thêm đáp án mới
    document.getElementById('add-answer').addEventListener('click', function () {
        const tableBody = document.querySelector('#answers-table tbody');
        const rowCount = tableBody.rows.length; // Lấy số dòng hiện tại
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <input type="text" name="answers[${rowCount}][content]" class="form-control" placeholder="Nhập đáp án">
            </td>
            <td>
                <select name="answers[${rowCount}][is_correct]" class="form-select">
                    <option value="1">Đúng</option>
                    <option value="0">Sai</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-answer">Xóa</button>
            </td>
        `;
        tableBody.appendChild(newRow);
    });

    // Xử lý xóa đáp án
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-answer')) {
            e.target.closest('tr').remove();
        }
    });
</script>

<script>
    ClassicEditor.create(document.querySelector('#editor2'), {
        ckfinder: {
            uploadUrl: '{{ route("admin.upload.ckeditor") }}?_token={{ csrf_token() }}'
        }
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection
