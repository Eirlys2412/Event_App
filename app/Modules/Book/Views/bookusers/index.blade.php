@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách người dùng và điểm của họ</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{ $users->currentPage() }} trong
                {{ $users->lastPage() }} trang</div>
        </div>

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">ID Người Dùng</th>
                        <th class="whitespace-nowrap">Tên Người Dùng</th>
                        <th class="whitespace-nowrap">Điểm</th>
                        <th class="text-center whitespace-nowrap">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookUsers as $bookUser)
                        <tr class="intro-x">
                            <td>{{ $bookUser['user']->id }}</td>
                            <td>{{ $bookUser['user']->full_name }}</td>
                            <td>{{ $bookUser['points'] }}</td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="{{ route('admin.bookusers.updatePoints', $bookUser['user']->id) }}"
                                        class="flex items-center mr-3">Cập nhật điểm</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
        <!-- END: Data List -->
    </div>
@endsection
