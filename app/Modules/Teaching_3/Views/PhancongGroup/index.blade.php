@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh Sách Phân Công Group
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="intro-y box p-5 mt-5">
                <!-- Thêm nút tạo mới -->
                <a href="{{ route('phanconggroup.create') }}" class="btn btn-primary mb-4">Thêm Phân Công Group Mới</a>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Group</th>
                            <th>Phân Công</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phancongGroups as $phancongGroup)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $phancongGroup->group->title ?? 'N/A' }}</td>
                                <td>
                                    @if($phancongGroup->phancong)
                                        {{ $phancongGroup->phancong->giangvien->mgv ?? 'N/A' }} - {{ $phancongGroup->phancong->hocphan->title ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <!-- Chỉnh sửa -->
                                    <a href="{{ route('phanconggroup.edit', $phancongGroup->id) }}" class="btn btn-warning btn-sm">Sửa</a>

                                    <!-- Xóa -->
                                    <form action="{{ route('phanconggroup.destroy', $phancongGroup->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phân công group này?');">
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
