@extends('backend.layouts.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thêm bình luận mới</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="box p-5">
            <!-- Hiển thị thông báo thành công -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
                    <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            <!-- Hiển thị lỗi validation -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
                    <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.comments.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mt-3">
                    <label for="item_type" class="form-label">Loại mục</label>
                    <select name="item_type" id="item_type" class="form-control" onchange="updateItemSelect(this)" required>
                        <option value="">Chọn loại mục</option>
                        <option value="blog" {{ $itemCode === 'blog' ? 'selected' : '' }}>Blog</option>
                        <option value="event" {{ $itemCode === 'event' ? 'selected' : '' }}>Event</option>
                    </select>
                    @error('item_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="item_id" class="form-label">Mục</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">Chọn mục</option>
                        @foreach ($blogs as $blog)
                            <option value="{{ $blog->id }}" data-type="blog" {{ $itemCode === 'blog' && $itemId == $blog->id ? 'selected' : '' }}>
                                {{ $blog->title }} (Blog)
                            </option>
                        @endforeach
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" data-type="event" {{ $itemCode === 'event' && $itemId == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} (Event)
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="parent_id" class="form-label">Phản hồi cho bình luận (nếu có)</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">Không có</option>
                        @foreach ($parentComments as $parent)
                            <option value="{{ $parent->id }}">{{ Str::limit($parent->content, 50) }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="comment_resources" class="form-label">Tài nguyên (ảnh, link, v.v.)</label>
                    <input type="file" name="comment_resources" id="comment_resources" class="form-control" accept="image/*">
                    @error('comment_resources')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-right mt-5">
                    <a href="{{ route('admin.comments.index', ['item_id' => $itemId, 'item_code' => $itemCode]) }}" class="btn btn-outline-secondary w-24 mr-1">Hủy</a>
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateItemSelect(select) {
        var itemType = select.value;
        var itemSelect = document.getElementById('item_id');
        var options = itemSelect.options;

        for (var i = 1; i < options.length; i++) {
            options[i].style.display = (options[i].getAttribute('data-type') === itemType) ? 'block' : 'none';
        }
        itemSelect.selectedIndex = 0; // Reset lựa chọn
    }

    document.addEventListener('DOMContentLoaded', function() {
        var itemTypeSelect = document.getElementById('item_type');
        updateItemSelect(itemTypeSelect);
    });
</script>
@endsection