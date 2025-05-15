@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        @if(isset($group))
            Bài đăng trong nhóm: {{ $group->name }}
        @else
            Danh sách bài đăng cộng đồng
        @endif
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        @if(isset($group))
            <a href="{{ route('admin.community.groups.show', $group->id) }}" class="btn btn-outline-secondary shadow-md mr-2">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Quay lại chi tiết nhóm
            </a>
            <a href="{{ route('admin.community.posts.create', ['group_id' => $group->id]) }}" class="btn btn-primary shadow-md">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tạo bài đăng mới
            </a>
        @else
            <a href="{{ route('admin.community.posts.create') }}" class="btn btn-primary shadow-md">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tạo bài đăng mới
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">BÀI ĐĂNG</th>
                    @if(!isset($group))
                        <th class="text-center whitespace-nowrap">NHÓM</th>
                    @endif
                    <th class="text-center whitespace-nowrap">NGƯỜI ĐĂNG</th>
                    <th class="text-center whitespace-nowrap">NGÀY ĐĂNG</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @if(count($posts) > 0)
                    @foreach($posts as $post)
                    <tr class="intro-x">
                        <td>
                            <a href="{{ route('admin.community.posts.show', $post->id) }}" class="font-medium whitespace-nowrap">{{ $post->title ?? 'Không có tiêu đề' }}</a>
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ Str::limit(strip_tags($post->content), 50) }}</div>
                        </td>
                        @if(!isset($group))
                            <td class="text-center">
                                <a href="{{ route('admin.community.groups.show', $post->group->id) }}" class="underline text-primary">
                                    {{ $post->group->name }}
                                </a>
                            </td>
                        @endif
                        <td class="text-center">{{ $post->user->full_name ?? $post->user->username }}</td>
                        <td class="text-center">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td class="w-40">
                            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                <input id="status-switch-{{ $post->id }}" class="form-check-input status-toggle" type="checkbox" {{ $post->status == 'active' ? 'checked' : '' }} data-id="{{ $post->id }}">
                            </div>
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('admin.community.posts.edit', $post->id) }}">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                                </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $post->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                                </a>
                            </div>

                            <!-- BEGIN: Delete Confirmation Modal -->
                            <div id="delete-confirmation-modal-{{ $post->id }}" class="modal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="p-5 text-center">
                                                <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                <div class="text-3xl mt-5">Bạn có chắc không?</div>
                                                <div class="text-slate-500 mt-2">Bạn thực sự muốn xóa bài đăng này? <br>Thao tác này không thể hoàn tác.</div>
                                            </div>
                                            <div class="px-5 pb-8 text-center">
                                                <form action="{{ route('admin.community.posts.destroy', $post->id) }}" method="post" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-24">Xóa</button>
                                                </form>
                                                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Hủy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Delete Confirmation Modal -->
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ isset($group) ? 5 : 6 }}" class="text-center py-4">
                            @if(isset($group))
                                Chưa có bài đăng nào trong nhóm này
                            @else
                                Chưa có bài đăng nào
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    
    <!-- BEGIN: Pagination -->
    @if(count($posts) > 0)
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $posts->links() }}
    </div>
    @endif
    <!-- END: Pagination -->
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Xử lý toggle trạng thái
        $('.status-toggle').on('change', function() {
            const id = $(this).data('id');
            const mode = $(this).prop('checked') ? 1 : 0;
            
            $.ajax({
                url: "{{ route('admin.community.posts.status') }}",
                type: "POST",
                data: {
                    id: id,
                    mode: mode,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.msg) {
                        toastr.success(response.msg);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Có lỗi xảy ra, vui lòng thử lại sau');
                    console.error(error);
                }
            });
        });
    });
</script>
@endsection 