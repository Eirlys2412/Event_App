@extends('backend.layouts.master')

@section ('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chỉnh sửa Bộ Đề Tự Luận
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->

        <form method="post" action="{{ route('admin.bode_tuluans.update', $bodeTuLuan->id) }}">
            @csrf
            @method('patch')
            <div class="intro-y box p-5">
                <!-- Tiêu đề -->
                <div class="mt-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="{{ old('title', $bodeTuLuan->title) }}" required>
                </div>

                <!-- Học phần -->
                <div class="mt-3">
                    <label for="hocphan_id" class="form-label">Học phần</label>
                    <select name="hocphan_id" id="hocphan_id" class="form-select">
                        @foreach($hocphan as $hoc_phan)
                            <option value="{{ $hoc_phan->id }}" 
                                    {{ $hoc_phan->id == $bodeTuLuan->hocphan_id ? 'selected' : '' }}>
                                {{ $hoc_phan->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="user_id" class="form-label">Người tạo</label>
                    <select name="user_id" id="user_id" class="form-select mt-2">
                        @foreach($users as $data)
                            <option value="{{ $data->id }}" {{ $data->id == old('user_id', $bodeTuLuan->user_id) ? 'selected' : '' }}>
                                {{ $data->username }}
                            </option>
                        @endforeach
                    </select>
                </div>
                

                <!-- Thời gian bắt đầu -->
                <div class="mt-3">
                    <label for="start_time" class="form-label">Thời gian bắt đầu</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" 
                           value="{{ old('start_time', \Carbon\Carbon::parse($bodeTuLuan->start_time)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <!-- Thời gian kết thúc -->
                <div class="mt-3">
                    <label for="end_time" class="form-label">Thời gian kết thúc</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" 
                           value="{{ old('end_time', \Carbon\Carbon::parse($bodeTuLuan->end_time)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <!-- Thời lượng -->
                <div class="mt-3">
                    <label for="time" class="form-label">Thời lượng (phút)</label>
                    <input type="number" name="time" id="time" class="form-control" 
                           value="{{ old('time', $bodeTuLuan->time) }}" required>
                </div>

                <!-- Tổng điểm -->
                <div class="mt-3">
                    <label for="total_points" class="form-label">Tổng điểm</label>
                    <input type="number" name="total_points" id="total_points" class="form-control" 
                           value="{{ old('total_points', $bodeTuLuan->total_points) }}" required>
                </div>

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
                                        <input 
                                            type="checkbox" 
                                            name="selected_questions[]" 
                                            value="{{ $cauHoi->id }}" 
                                            class="form-check-input"
                                            {{ in_array($cauHoi->id, array_column($selectedQuestions, 'id_question')) ? 'checked' : '' }}
                                        >
                                    </td>
                                    <td>{{ $cauHoi->content }}</td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="points[{{ $cauHoi->id }}]" 
                                            class="form-control" 
                                            step="0.1" 
                                            placeholder="Nhập điểm"
                                            value="{{ collect($selectedQuestions)->firstWhere('id_question', $cauHoi->id)['points'] ?? '' }}"
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                


                <!-- Hiển thị lỗi -->
                <div class="mt-3">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Nút submit -->
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
@endsection
