@extends('backend.layouts.master')

@section('content')

<h2 class="intro-y text-lg font-medium mt-10">Chỉnh sửa Câu hỏi</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <form action="{{ route('admin.tuluancauhoi.update', $tuluancauhoi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $tuluancauhoi->content) }}</textarea>
                @error('content')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="hocphan_id" class="form-label">Học phần</label>
                <select name="hocphan_id" id="hocphan_id" class="form-select" required>
                    <option value="">Chọn học phần</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}" {{ $tuluancauhoi->hocphan_id == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                    @endforeach
                </select>
                @error('hocphan_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật Câu hỏi</button>
        </form>
    </div>
</div>

@endsection