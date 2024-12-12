@extends('backend.layouts.master')

@section('content')
<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10">
        Kết quả tìm kiếm loại sự kiện
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$EventTypes->currentPage()}} trong {{$EventTypes->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{ route('admin.event_type.search') }}" method="get">
                        @csrf
                        <input type="text" name="datasearch" value="{{ request('datasearch') }}" class="ipsearch form-control w-56 box pr-10" placeholder="Tìm kiếm...">
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
                        <th class="whitespace-nowrap">ID</th>
                        <th class="whitespace-nowrap">Tiêu đề</th>
                        <!-- <th class="whitespace-nowrap">Slug</th> -->
                        <th class="whitespace-nowrap">Loại địa điểm</th>
                        <th class="whitespace-nowrap">Tên địa điểm tổ chức</th>
                        <th class="text-center whitespace-nowrap">Trạng thái</th>
                        <th class="text-center whitespace-nowrap">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($EventTypes as $eventType)
                        <tr class="intro-x">
                            <td>{{ $eventType->id }}</td>
                            <td>{{ $eventType->title }}</td>
                            <!-- <td>{{ $eventType->slug }}</td> -->
                            <td>{{ $eventType->location_type }}</td>
                            <td>{{ $eventType->location_address }}</td>
                            <td class="text-center">
                                <input type="checkbox" 
                                       data-toggle="switchbutton" 
                                       data-onlabel="active"
                                       data-offlabel="inactive"
                                       {{ $eventType->status == 'active' ? 'checked' : '' }}
                                       data-size="sm"
                                       name="toggle"
                                       value="{{ $eventType->id }}"
                                       data-style="ios">
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{ route('admin.event_type.edit', $eventType->id) }}" class="flex items-center mr-3">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.event_type.destroy', $eventType->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{ $eventType->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
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
                {{$EventTypes->links('vendor.pagination.tailwind')}}
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
</div>
@endsection
