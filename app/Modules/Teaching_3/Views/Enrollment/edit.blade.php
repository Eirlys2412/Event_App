@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh Sửa Enrollment
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box p-5">
                <form action="{{ route('enrollment.update', $enrollment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="student_id" class="form-label">User</label>
                        <select name="student_id" id="student_id" class="form-control">
                            <option value="">-- Chọn User --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $enrollment->student_id == $student->id ? 'selected' : '' }}>{{ $student->mssv }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="phancong_id" class="form-label">Phân Công</label>
                        <select name="phancong_id" id="phancong_id" class="form-control">
                            <option value="">-- Chọn Phân Công --</option>
                            @foreach($phancongs as $phancong)
                                <option value="{{ $phancong->id }}" {{ $enrollment->phancong_id == $phancong->id ? 'selected' : '' }}>
                                    {{ $phancong->giangvien->mgv ?? 'N/A' }} - {{ $phancong->hocphan->title ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="timespending" class="form-label">Thời Gian Học (giờ)</label>
                        <input type="number" name="timespending" id="timespending" class="form-control" placeholder="Nhập thời gian học" value="{{ old('timespending', $enrollment->timespending) }}">
                    </div>

                    <div class="mb-4">
                        <label for="process" class="form-label">Hoàn Thành (%)</label>
                        <input type="number" name="process" id="process" class="form-control" placeholder="Nhập phần trăm hoàn thành" value="{{ old('process', $enrollment->process) }}">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Trạng Thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="success" {{ $enrollment->status == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="finished" {{ $enrollment->status == 'finished' ? 'selected' : '' }}>Finished</option>
                            <option value="rejected" {{ $enrollment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        <a href="{{ route('enrollment.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
