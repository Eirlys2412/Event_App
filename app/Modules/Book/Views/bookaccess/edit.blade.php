@extends('backend.layouts.master')

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Sửa điểm truy cập sách
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <form action="{{ route('admin.bookaccess.update', $bookAccess->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="intro-y box p-5">
                    <div class="mt-3">
                        <label for="book_id">Chọn sách</label>
                        <select name="book_id" id="book_id" class="form-control" required>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}" {{ $bookAccess->book_id == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="point_access">Điểm truy cập</label>
                        <input type="number" name="point_access" id="point_access" class="form-control"
                            value="{{ $bookAccess->point_access }}" required>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.bookaccess.index') }}" class="btn btn-secondary ml-2">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
