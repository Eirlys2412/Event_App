@extends('backend.layouts.master')

@section('content')
    <div class="container">
        <h1>Danh sách chuyên ngành</h1>
        <ol class="breadcrumb">
            {!! $breadcrumb !!}
        </ol>

        <div class="mb-3">
            <a href="{{ route('admin.major.create') }}" class="btn btn-primary">Tạo chuyên ngành</a>
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
                    <th>Tên chuyên ngành</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($majors as $major)
                    <tr>
                        <td>{{ $major->id }}</td>
                        <td>{{ $major->title }}</td>
                        <td>
                            <span class="badge badge-{{ $major->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($major->status) }}
                            </span>
                        </td>
                        <td>{{ $major->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.major.edit', $major->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.major.destroy', $major->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $majors->links() }}
    </div>
@endsection
