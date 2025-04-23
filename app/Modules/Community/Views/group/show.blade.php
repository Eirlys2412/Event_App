@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chi tiết nhóm: {{ $group->name }}</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('admin.community.groups.index') }}" class="btn btn-outline-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Quay lại danh sách
        </a>
        <a href="{{ route('admin.community.groups.edit', $group->id) }}" class="btn btn-primary shadow-md mr-2">
            <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.community.members.index', $group->id) }}" class="btn btn-success shadow-md mr-2">
            <i data-lucide="users" class="w-4 h-4 mr-1"></i> Quản lý thành viên
        </a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Cover image -->
    <div class="intro-y box col-span-12">
        <div class="h-40 md:h-56 xl:h-80 overflow-hidden relative">
            @if($group->cover_image)
                <img src="{{ asset('storage/' . $group->cover_image) }}" class="w-full h-full object-cover" alt="Cover Image">
            @else
                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                    <span class="text-slate-500">Chưa có ảnh bìa</span>
                </div>
            @endif
        </div>
        
        <div class="px-5 py-4 border-t border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-medium">{{ $group->name }}</h2>
                    <div class="flex items-center mt-2">
                        <span class="bg-{{ $group->privacy == 'public' ? 'success' : ($group->privacy == 'private' ? 'primary' : 'danger') }}/20 text-{{ $group->privacy == 'public' ? 'success' : ($group->privacy == 'private' ? 'primary' : 'danger') }} rounded px-2 py-1 text-sm mr-2">
                            {{ $group->privacy == 'public' ? 'Công khai' : ($group->privacy == 'private' ? 'Riêng tư' : 'Ẩn') }}
                        </span>
                        <span class="bg-{{ $group->status == 'active' ? 'success' : 'danger' }}/20 text-{{ $group->status == 'active' ? 'success' : 'danger' }} rounded px-2 py-1 text-sm">
                            {{ $group->status == 'active' ? 'Hoạt động' : 'Đã khóa' }}
                        </span>
                    </div>
                    <p class="mt-4 text-slate-600">{{ $group->description }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thông tin chung và bài đăng -->
    <div class="col-span-12 lg:col-span-8 intro-y">
        <div class="box p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-medium">Bài đăng gần đây</h3>
                <a href="{{ url('admin/group/'.$group->id.'/posts') }}" class="text-primary text-sm">Xem tất cả</a>
            </div>
            @if($group->posts->count() > 0)
                <div class="divide-y">
                @foreach($group->posts->take(5) as $post)
    <div class="py-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                @if($post->user)
                    <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('backend/images/profile-1.jpg') }}" class="w-full h-full object-cover" alt="User Avatar">
                @else
                    <img src="{{ asset('backend/images/profile-1.jpg') }}" class="w-full h-full object-cover" alt="No User Avatar">
                @endif
            </div>
            <div>
                <div class="font-medium">{{ $post->user ? ($post->user->full_name ?? $post->user->username) : 'Unknown' }}</div>
                <div class="text-slate-500 text-xs">{{ $post->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <div class="mt-2">
            @if($post->title)
                <h4 class="font-medium">{{ $post->title }}</h4>
            @endif
            <p class="text-slate-600 mt-1">{{ Str::limit($post->content, 200) }}</p>
        </div>
    </div>
@endforeach
                </div>
                @if($group->posts->count() > 5)
                    <div class="mt-3 text-center">
                        <a href="#" class="btn btn-outline-secondary btn-sm">Xem thêm bài đăng</a>
                    </div>
                @endif
            @else
                <div class="text-slate-500 text-center py-4">Chưa có bài đăng nào trong nhóm</div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-span-12 lg:col-span-4 intro-y">
        <!-- Thông tin nhóm -->
        <div class="box p-5 mb-5">
            <h3 class="text-lg font-medium mb-3">Thông tin nhóm</h3>
            <div class="mt-3">
                <div class="flex items-center">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i>
                    <span>Ngày tạo: {{ $group->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center mt-2">
                    <i data-lucide="user" class="w-4 h-4 text-slate-500 mr-2"></i>
                    <span>
                        Người tạo: 
                        @if($group->creator) 
                            {{ $group->creator->full_name ?? $group->creator->username }}
                        @else
                            <?php
                            $creator = \App\Models\User::find($group->created_by);
                            ?>
                            @if($creator)
                                {{ $creator->full_name ?? $creator->username }}
                            @else
                                Không xác định
                            @endif
                        @endif
                    </span>
                </div>
                <div class="flex items-center mt-2">
                    <i data-lucide="users" class="w-4 h-4 text-slate-500 mr-2"></i>
                    <span>Thành viên: {{ $group->members->count() }}</span>
                </div>
                <div class="flex items-center mt-2">
                    <i data-lucide="message-square" class="w-4 h-4 text-slate-500 mr-2"></i>
                    <span>Bài đăng: {{ $group->posts->count() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Thành viên nhóm -->
        <div class="box p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-medium">Thành viên nhóm</h3>
                <a href="{{ route('admin.community.members.index', $group->id) }}" class="text-primary text-sm">Xem tất cả</a>
            </div>
            @if($group->members->count() > 0)
                <div class="divide-y">
                @foreach($group->members->take(5) as $member)
    <div class="py-3 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : asset('backend/images/profile-1.jpg') }}" class="w-full h-full object-cover" alt="User Avatar">
            </div>
            <div>
                <div class="font-medium">{{ $member->full_name ?? $member->username }}</div>
                <div class="text-xs text-slate-500">{{ $member->pivot->role }}</div> <!-- Sửa role từ pivot -->
            </div>
        </div>
        <div>
            <span class="bg-{{ $member->pivot->status == 'active' ? 'success' : 'danger' }}/20 text-{{ $member->pivot->status == 'active' ? 'success' : 'danger' }} rounded px-2 py-1 text-xs">
                {{ $member->pivot->status == 'active' ? 'Hoạt động' : 'Đã chặn' }}
            </span>
        </div>
    </div>
@endforeach
                </div>
            @else
                <div class="text-slate-500 text-center py-4">Chưa có thành viên nào</div>
            @endif
        </div>
    </div>
</div>

@endsection 