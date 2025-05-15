@extends('backend.layouts.master')
@section('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm Bộ Đề Tự Luận
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{route('admin.bode_tuluans.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <div class="mt-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="mt-3">
                    <label for="hocphan_id" class="form-label">Học Phần</label>
                    <select name="hocphan_id" class="form-select mt-2">
                        @foreach($hocphan as $data)
                            <option value="{{$data->id}}">{{ $data->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="user_id" class="form-label">Người tạo</label>
                    <select name="user_id" class="form-select mt-2">
                        @foreach($users as $data)
                            <option value="{{$data->id}}">{{ $data->username }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="start_time" class="form-label">Thời gian bắt đầu</label>
                    <input type="datetime-local" id="start_time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                </div>

                <div class="mt-3">
                    <label for="end_time" class="form-label">Thời gian kết thúc</label>
                    <input type="datetime-local" id="end_time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                </div>

                 <!-- Tổng thời gian -->
                 <div class="mt-3">
                    <label for="time" class="form-label">Tổng thời gian (phút)</label>
                    <input type="number" name="time" id="time" class="form-control" value="{{ old('time') }}" placeholder="Nhập thời gian">
                </div>

                <div class="mt-3">
                    <label for="total_points" class="form-label">Tổng điểm</label>
                    <input type="number" id="total_points" name="total_points" class="form-control" value="{{ old('total_points') }}" min="0" required>
                </div>

                <div class="mt-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="select-tags" name="tag_ids[]" multiple placeholder="Chọn tags..." autocomplete="off">
                        @foreach ($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->title}}</option>
                        @endforeach
                    </select>
                </div>

                 <!-- Câu hỏi -->
                <div class="mt-3">
                <label for="questions" class="form-label">Danh sách câu hỏi</label>
                <small class="text-gray-500">Chọn câu hỏi và nhập điểm cho từng câu</small>

                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Nội dung câu hỏi</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cauHois as $cauHoi)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_questions[]" value="{{ $cauHoi->id }}" class="form-check-input">
                                </td>
                                <td>{{ $cauHoi->content }}</td>
                                <td>
                                    <input type="number" name="points[{{ $cauHoi->id }}]" class="form-control" step="0.1" placeholder="Nhập điểm">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                 </table>
                </div>


                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script>
    var select = new TomSelect('#select-tags', {
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
    select.clear();
</script>

<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#questions'), {
            mediaEmbed: { previewsInData: true }
        })
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
</script>

@endsection
