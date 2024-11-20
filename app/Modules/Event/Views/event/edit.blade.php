@extends('backend.layouts.master')

@section('content')
    <div class="container">
        
        <!-- Cập nhật route ở đây -->
        <form action="{{ route('admin.eventtype.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Tên sự kiện</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="inactive" {{ $event->status == 'inactive' ? 'selected' : '' }}>Không kích hoạt</option>
                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <br><button type="submit" class="btn btn-success">Cập nhật</button><br>
        </form>
    </div>
@endsection
