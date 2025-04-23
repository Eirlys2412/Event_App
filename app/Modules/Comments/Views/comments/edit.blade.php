@extends('backend.layouts.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Chỉnh sửa bình luận</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="box p-5">
            <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}">
                @csrf
                @method('PUT')
                <div class="mt-3">
                    <label for="content" class="form-label">Nội dung</label>
                    <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $comment->content) }}</textarea>
                </div>
                <div class="text-right mt-5">
                    <a href="{{ route('admin.comments.index', ['item_id' => $comment->item_id, 'item_code' => $comment->item_code]) }}" class="btn btn-outline-secondary w-24 mr-1">Hủy</a>
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection