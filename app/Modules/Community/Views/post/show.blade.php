<!-- resources/views/post/show.blade.php -->
@extends('backend.layouts.master')

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chi tiết bài đăng: {{ $post->title }}</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ url('admin/community/groups/' . $post->group_id) }}" class="btn btn-outline-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Quay lại nhóm
        </a>
    </div>
</div>

<div class="intro-y box mt-5 p-5">
    <div class="flex items-center">
        <div class="w-12 h-12 rounded-full overflow-hidden mr-3">
            @if($post->user && $post->user->avatar)
                <img src="{{ asset('storage/' . $post->user->avatar) }}" class="w-full h-full object-cover" alt="User Avatar">
            @else
                <img src="{{ asset('backend/images/profile-1.jpg') }}" class="w-full h-full object-cover" alt="Default Avatar">
            @endif
        </div>
        <div>
            <div class="font-medium">{{ $post->user ? ($post->user->full_name ?? $post->user->username) : 'Unknown' }}</div>
            <div class="text-slate-500 text-xs">{{ $post->created_at->diffForHumans() }}</div>
        </div>
    </div>
    <div class="mt-4">
        <h3 class="text-xl font-medium">{{ $post->title }}</h3>
        <p class="mt-2 text-slate-600">{{ $post->content }}</p>
    </div>
    @if($post->media && is_array($post->media))
        <div class="mt-4">
            <h4 class="text-lg font-medium">Media</h4>
            <div class="grid grid-cols-2 gap-4 mt-2">
                @foreach($post->media as $mediaUrl)
                    <img src="{{ asset('storage/' . $mediaUrl) }}" class="w-full h-auto object-cover rounded" alt="Post Media">
                @endforeach
            </div>
        </div>
    @endif
    <div class="mt-4">
        <span class="bg-{{ $post->status == 'active' ? 'success' : 'danger' }}/20 text-{{ $post->status == 'active' ? 'success' : 'danger' }} rounded px-2 py-1 text-sm">
            {{ $post->status == 'active' ? 'Hoạt động' : 'Ẩn' }}
        </span>
    </div>
</div>
@endsection