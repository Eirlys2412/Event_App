@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chỉnh Sửa Phân Công Group
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="intro-y box p-5">
                <form action="{{ route('phanconggroup.update', $phancongGroup->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Chọn Group -->
                    <div class="mt-3">
                        <label for="group_id" class="form-label">Chọn Group</label>
                        <select name="group_id" id="group_id" class="form-control" required>
                            <option value="" disabled>-- Chọn Group --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" 
                                    {{ $phancongGroup->group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn Phân Công -->
                    <div class="mt-3">
                        <label for="phancong_id" class="form-label">Chọn Phân Công</label>
                        <select name="phancong_id" id="phancong_id" class="form-control" required>
                            <option value="" disabled>-- Chọn Phân Công --</option>
                            @foreach($phancongs as $phancong)
                                <option value="{{ $phancong->id }}" 
                                    {{ $phancongGroup->phancong_id == $phancong->id ? 'selected' : '' }}>
                                    {{ $phancong->giangvien->mgv ?? 'N/A' }} - {{ $phancong->hocphan->title ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nút Lưu -->
                    <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        <a href="{{ route('phanconggroup.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
