@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh Sách Enrollment
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="intro-y box p-5 mt-5">
                <!-- Thêm nút tạo mới -->
                <a href="{{ route('enrollment.create') }}" class="btn btn-primary mb-4">Thêm Enrollment Mới</a>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sinh viên</th>
                            <th>Phân Công</th>
                            <th>Thời Gian Học</th>
                            <th>Hoàn Thành (%)</th>
                            <th>Trạng Thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enrollment->students->mssv ?? 'N/A' }}</td>
                                <td>
                                    @if($enrollment->phancong)
                                        {{ $enrollment->phancong->giangvien->mgv ?? 'N/A' }} - {{ $enrollment->phancong->hocphan->title ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $enrollment->timespending }} giờ</td>
                                <td>{{ $enrollment->process }}%</td>
                                <td>{{ ucfirst($enrollment->status) }}</td>
                                <td>
                                    <!-- Chỉnh sửa -->
                                    <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                                    <!-- Xóa -->
                                    <form action="{{ route('enrollment.destroy', $enrollment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa enrollment này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
