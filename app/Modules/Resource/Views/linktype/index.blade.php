@extends('backend.layouts.master')

@section('content')

    <h2 class="intro-y text-lg font-medium mt-10">Danh sách loại liên kết tài nguyên</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.resource-link-types.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm loại liên kết tài nguyên
            </a>
        </div>
        
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap" style="width: 40%;">Tiêu đề</th>
                        <th class="whitespace-nowrap" style="width: 30%;">Mã</th>
                        <th class="whitespace-nowrap" style="width: 20%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($resourceLinkTypes) && count($resourceLinkTypes) > 0)
                        @foreach ($resourceLinkTypes as $resourceLinkType)
                            <tr class="intro-x">
                                <td class="text-blue-600">{{ $resourceLinkType->title }}</td>
                                <td class="text-gray-800">{{ $resourceLinkType->code }}</td>

                                <td class="table-report__action">
                                    <div class="flex justify-start items-center">
                                        <a href="{{ route('admin.resource-link-types.edit', $resourceLinkType->id) }}" class="flex items-center mr-3 text-blue-500">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Sửa
                                        </a>
                                        
                                        <form action="{{ route('admin.resource-link-types.destroy', $resourceLinkType->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center text-red-500" 
                                                style="line-height: 1;" 
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> 
                                                <span class="ml-1">Xóa</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                <p class="text-lg text-red-600">Không tìm thấy loại liên kết nào!</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{ $resourceLinkTypes->links() }}

@endsection
