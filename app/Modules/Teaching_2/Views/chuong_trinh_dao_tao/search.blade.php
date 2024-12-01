@extends('backend.layouts.master')
@section('content')

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm Chương Trình Đào Tạo
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$programs->currentPage()}} trong {{$programs->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('admin.chuong_trinh_dao_tao.search')}}" method="get">
                        @csrf
                        <input type="text" name="datasearch" value="{{$searchData}}" class="ipsearch form-control w-56 box pr-10" placeholder="Tìm kiếm...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>
        
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">MÃ CHƯƠNG TRÌNH ĐÀO TẠO</th>
                        <th class="whitespace-nowrap">TÊN CHƯƠNG TRÌNH ĐÀO TẠO</th>
                        <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr class="intro-x">
                        <td>{{ $program->id }}</td>
                        <td>{{ $program->title }}</td>
                        <td class="text-center">
                            <input type="checkbox" 
                                data-toggle="switchbutton" 
                                data-onlabel="active"
                                data-offlabel="inactive"
                                {{ $program->status == "active" ? "checked" : "" }}
                                data-size="sm"
                                name="toggle"
                                value="{{ $program->id }}"
                                data-style="ios">
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{route('admin.chuong_trinh-dao_tao.edit', $program->id)}}" class="flex items-center mr-3">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{route('admin.chuong_trinh_dao_tao.destroy', $program->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $program->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </a>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{$programs->links('vendor.pagination.tailwind')}}
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
</div>

@endsection
