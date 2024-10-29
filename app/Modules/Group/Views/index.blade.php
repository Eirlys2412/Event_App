@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách nhóm người dùng
    </h2>

    <!-- Nút thêm nhóm -->
    <div class="intro-y flex items-center mt-5">
        <a href="{{ route('admin.group.create') }}" class="btn btn-primary shadow-md mr-2">Thêm nhóm</a>
    </div>

    <!-- Danh sách nhóm -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        @if (isset($groups) && count($groups) > 0)
            @foreach ($groups as $group)
                <div class="intro-y col-span-12 sm:col-span-6 lg:col-span-4">
                    <div class="relative card p-4 border rounded-lg shadow-md transition-transform transform hover:scale-105 h-52 flex">
                        
                        <!-- Hiển thị hình ảnh vuông ở góc phải -->
                        @if ($group->image)
                            <div class="flex-shrink-0 w-48 h-35 flex items-center justify-center overflow-hidden border border-gray-300 p-1">
                                <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->title }}" class="object-cover w-full h-full rounded-lg">
                            </div>
                        @else
                            <div class="flex-shrink-0 w-48 h-35 flex items-center justify-center overflow-hidden border border-gray-300 p-1">
                                <img src="default-image-url.jpg" alt="Default Image" class="object-cover w-full h-full rounded-lg">
                            </div>
                        @endif

                        <!-- Thông tin nhóm -->
                        <div class="relative z-10 w-full h-full p-4 flex flex-col justify-between ml-2">
                            <h5 class="text-lg font-medium text-gray-800">{{ $group->title }}</h5>
                            <p class="text-sm text-gray-600">Slug: <strong>{{ $group->slug }}</strong></p>
                            <p class="text-sm text-gray-600">Trạng thái: 
                                @if ($group->status == 'active')
                                    <span class="badge bg-light text-dark">Hoạt Động</span>
                                @else
                                    <span class="badge bg-light text-dark">Không Hoạt Động</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-600">Riêng tư: 
                                @if ($group->private)
                                    <span class="badge bg-light text-dark">Riêng Tư</span>
                                @else
                                    <span class="badge bg-light text-dark">Công Khai</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-600">Mô tả:</p>
                            <p class="text-sm text-gray-600 overflow-hidden h-16 line-clamp-4">{{ nl2br(e(strip_tags($group->description))) }}</p>                        </div>

                        <!-- Nút Sửa và Nút Xóa -->
                        <div class="flex justify-between mt-2">  
                            <a href="{{ route('admin.groupmember.index', $group->id) }}" class="btn btn-sm flex-1 mr-1 h-10 text-center bg-blue-500 text-black rounded">Danh sách</a>
                            <a href="{{ route('admin.groupmember.create', $group->id) }}" class="btn btn-sm flex-1 ml-1 h-10 text-center bg-green-500 text-black rounded">Thêm</a>
                        </div>
                        <div class="flex justify-between mt-2">  
                            <a href="{{ route('admin.group.edit', $group->id) }}" class="btn btn-sm flex-1 mr-1 h-10 text-center bg-yellow-500 text-black rounded">Sửa</a>
                            
                            <form action="{{ route('admin.group.destroy', $group->id) }}" method="POST" class="flex-1 ml-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm w-full h-10 text-center bg-red-600 text-black rounded" onclick="confirmDelete(this)">Xóa</button>
                            </form>
                        </div>
                        
                       
                    </div>
                </div>
            @endforeach
        @else
            <div class="intro-y col-span-12">
                <div class="text-center p-4">
                    <p class="text-lg text-red-600">Không tìm thấy nhóm nào!</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-5">
        {{ $groups->links() }}
    </div>

    <script>
        function confirmDelete(button) {
            if (confirm('Bạn có chắc chắn muốn xóa nhóm này?')) {
                button.closest('form').submit();
            }
        }
    </script>

    <style>
        /* Định nghĩa kiểu dáng cho nút Sửa và Xóa */
        .btn {
            padding: 0.5rem 1rem; /* Padding */
            border-radius: 0.25rem; /* Bo góc */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu khi hover */
        }

        .btn:hover {
            opacity: 0.9; /* Hiệu ứng mờ khi hover */
        }
    </style>
@endsection
