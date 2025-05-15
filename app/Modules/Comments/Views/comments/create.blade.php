@extends('backend.layouts.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thêm bình luận mới</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="box p-5">
            @if (session('success'))
                <div class="alert alert-success flex items-center mb-2" role="alert">
                    <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger flex items-center mb-2" role="alert">
                    <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.comments.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mt-3">
                    <label for="item_type" class="form-label">Loại mục</label>
                    <select name="item_type" id="item_type" class="form-control" onchange="updateItemSelect(this)" required>
                        <option value="">Chọn loại mục</option>
                        <option value="blog" {{ ($itemCode ?? '') === 'blog' ? 'selected' : '' }}>Blog</option>
                        <option value="event" {{ ($itemCode ?? '') === 'event' ? 'selected' : '' }}>Event</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label for="item_id" class="form-label">Mục</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">Chọn mục</option>
                        @if (!empty($blogs))
                            @foreach ($blogs as $blog)
                                <option value="{{ $blog->id }}" data-type="blog" {{ ($itemCode ?? '') === 'blog' && ($itemId ?? '') == $blog->id ? 'selected' : '' }}>
                                    {{ $blog->title }} (Blog)
                                </option>
                            @endforeach
                        @endif
                        @if (!empty($events))
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" data-type="event" {{ ($itemCode ?? '') === 'event' && ($itemId ?? '') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }} (Event)
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung *</label>
                    <textarea name="content" id="content" class="form-control" rows="4" required>{{ old('content') }}</textarea>
                </div>

                <div class="mt-3">
                    <label for="parent_id" class="form-label">Phản hồi bình luận (nếu có)</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">Không có</option>
                        @if (!empty($parentComments))
                            @foreach ($parentComments as $parent)
                                <option value="{{ $parent->id }}">{{ Str::limit($parent->content, 50) }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mt-3">
                    <label for="comment_resources" class="form-label">Tài nguyên đính kèm</label>
                    <input type="file" name="comment_resources" id="comment_resources" class="form-control" accept="image/*">
                </div>

                <div class="text-right mt-5">
                    <a href="{{ route('admin.comments.index', ['item_id' => $itemId, 'item_code' => $itemCode]) }}" class="btn btn-outline-secondary mr-2">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu</button>
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
        itemSelect.selectedIndex = 0;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateItemSelect(document.getElementById('item_type'));
    });
</script>
@endsection
