@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh Sách Loại Chứng Chỉ</h2>

    <a href="{{ route('loai_chungchi.create') }}" class="btn btn-primary mb-4">Thêm Loại Chứng Chỉ Mới</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loaiChungchis as $loaiChungchi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loaiChungchi->title }}</td>
                    <td>{{ $loaiChungchi->status }}</td>
                    <td>
                        <a href="{{ route('loai_chungchi.edit', $loaiChungchi->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                        <form action="{{ route('loai_chungchi.destroy', $loaiChungchi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa loại chứng chỉ này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
