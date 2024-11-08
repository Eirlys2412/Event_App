@extends('backend.layouts.master')

@section('content')
    <div class="container">
        <h1>Chỉnh sửa chuyên ngành</h1>
        <ol class="breadcrumb">
            {!! $breadcrumb !!}
        </ol>

        <form action="{{ route('admin.major.update', $major->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Tên chuyên ngành</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $major->title) }}" required>
                @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $major->description) }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" {{ $major->status == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="inactive" {{ $major->status == 'inactive' ? 'selected' : '' }}>Không kích hoạt</option>
                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
        </form>
    </div>
@endsection
    