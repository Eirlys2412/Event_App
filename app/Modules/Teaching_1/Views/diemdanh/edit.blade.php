@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa Sinh Viên</h2>
</div>

<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('diemdanh.update', $diemdanh->diemdanh_id) }}">
            @csrf
            @method('PUT')
            
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

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection
