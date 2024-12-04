
@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách loại liên kết câu hỏi tự luận</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('admin.tuluancauhoi-link-types.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Thêm loại liên kết
            </a>
        </div>

        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap" style="width: 40%;">Tên loại liên kết</th>
                        <th class="whitespace-nowrap" style="width: 30%;">Mã</th>
                        <th class="whitespace-nowrap" style="width: 30%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($linkTypes) && count($linkTypes) > 0)
                        @foreach ($linkTypes as $type)
                            <tr class="intro-x">
                                <td class="text-blue-600">{{ $type->title }}</td>
                                <td class="text-gray-800">{{ $type->code }}</td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-start items-center">
                                        <a href="{{ route('admin.tuluancauhoi-link-types.edit', $type->id) }}" class="flex items-center mr-3 text-blue-500">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.tuluancauhoi-link-types.destroy', $type->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center text-red-500" onclick="return confirm('Bạn có chắc chắn muốn xóa loại liên kết này không?');">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                <span>Xóa</span>
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

    {{ $linkTypes->links() }}
@endsection