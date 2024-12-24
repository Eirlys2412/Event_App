@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh Sách Phân Công
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="intro-y box p-5 mt-5">
                <!-- Thêm nút tạo phân công mới -->
                <a href="{{ route('phancong.create') }}" class="btn btn-primary mb-4">Thêm Phân Công Mới</a>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Giảng viên</th>
                            <th>Học phần</th>
                            <th>Học kỳ</th>
                            <th>Năm học</th>
                            <th>Ngày phân công</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phancongs as $phancong)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $phancong->giangvien->mgv }}</td>
                                <td>{{ $phancong->hocphan->title }}</td>
                                <td>{{ $phancong->hocky->so_hoc_ky }}</td>
                                <td>{{ $phancong->namhoc->nam_hoc }}</td>
                                <td>{{ \Carbon\Carbon::parse($phancong->ngayphancong)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($phancong->time_start)->format('d-m-Y') ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($phancong->time_end)->format('d-m-Y') ?? 'N/A' }}</td>
                                <td>
                                    <!-- Chỉnh sửa -->
                                    <a href="{{ route('phancong.edit', $phancong->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                                    <!-- Xóa -->
                                    <form action="{{ route('phancong.destroy', $phancong->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa phân công này?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="mt-4">
                    {{ $phancongs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
