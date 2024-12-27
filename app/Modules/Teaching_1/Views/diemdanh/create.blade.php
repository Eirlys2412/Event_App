@extends('backend.layouts.master')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">Thêm Điểm Danh</h2>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="box p-5">
            <form action="{{ route('diemdanh.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-12 gap-4">
                    <!-- Sinh viên -->
                    <div class="col-span-12 sm:col-span-6">
                        <label for="sinhvien_id" class="form-label">Sinh Viên</label>
                        <select id="sinhvien_id" name="sinhvien_id" class="form-control" required>
                            <option value="">Chọn sinh viên</option>
                            @foreach($sinhviens as $sinhvien)
                                <option value="{{ $sinhvien->id }}" {{ old('sinhvien_id') == $sinhvien->id ? 'selected' : '' }}>
                                    {{ $sinhvien->mssv }}
                                </option>
                            @endforeach
                        </select>

                    </div>


                    <!-- Học phần -->
                    <div class="col-span-12 sm:col-span-6">
                        <label for="hocphan_id" class="form-label">Học Phần</label>
                        <select name="hocphan_id" id="hocphan_id" class="form-control">
                            <option value="">Chọn Học Phần</option>
                            @foreach($hocphans as $hocphan)
                                <option value="{{ $hocphan->id }}">
                                    {{ $hocphan->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Thời gian -->
                    <div class="col-span-12 sm:col-span-6">
                        <label for="time" class="form-label">Thời Gian</label>
                        <input type="datetime-local" id="time" name="time" class="form-control" required>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-span-12 sm:col-span-6">
                        <label for="trangthai" class="form-label">Trạng Thái</label>
                        <select id="trangthai" name="trangthai" class="form-control" required>
                            <option value="có mặt">Có mặt</option>
                            <option value="vắng mặt">Vắng mặt</option>
                            <option value="muộn">Muộn</option>
                        </select>
                    </div>

                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Lưu Điểm Danh</button>
                    <a href="{{ route('diemdanh.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
