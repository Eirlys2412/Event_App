@extends('backend.layouts.master')

@section('content')
    <div class="container">
        <!-- <ol class="breadcrumb">
            {!! $breadcrumb !!}
        </ol> -->

        <div class="mb-3">
            <!-- Cập nhật route ở đây -->
            <a href="{{ route('admin.eventtype.create') }}" class="btn btn-primary">Tạo sự kiện</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sự kiện</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->description }}</td>
                        <td>
                            <span class="badge badge-{{ $event->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td>{{ $event->created_at->format('d/m/Y') }}</td>
                        <td>
                            <!-- Cập nhật route edit -->
                            <a href="{{ route('admin.eventtype.edit', $event->id) }}" class="btn btn-warning">Sửa</a>
                            <!-- Cập nhật route destroy -->
                            <form action="{{ route('admin.eventtype.destroy', $event->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $events->links() }}
    </div>
@endsection
