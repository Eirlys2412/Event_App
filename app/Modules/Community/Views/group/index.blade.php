@extends('backend.layouts.master')
@section('content')

<h2 class="intro-y text-lg font-medium mt-10">
    Danh sách nhóm cộng đồng
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.community.groups.create') }}" class="btn btn-primary shadow-md mr-2">Tạo nhóm mới</a>
        
        <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$groups->currentPage()}} trong {{$groups->lastPage()}} trang</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="{{ route('admin.community.groups.index') }}" method="get">
                    <input type="text" name="search" class="form-control w-56 box pr-10" placeholder="Tìm kiếm...">
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
                    <th class="whitespace-nowrap">TÊN NHÓM</th>
                    <th class="text-center whitespace-nowrap">NGƯỜI TẠO</th>
                    <th class="text-center whitespace-nowrap">QUYỀN RIÊNG TƯ</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr class="intro-x">
                    <td>
                        <a href="{{ route('admin.community.groups.show', $group->id) }}" class="font-medium whitespace-nowrap">{{ $group->name }}</a>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ Str::limit($group->description, 50) }}</div>
                    </td>
                    <td class="text-center">
                        @if($group->creator)
                            {{ $group->creator->full_name ?? $group->creator->username }}
                        @else
                            <?php $creator = \App\Models\User::find($group->created_by); ?>
                            @if($creator)
                                {{ $creator->full_name ?? $creator->username }}
                            @else
                                <span class="text-gray-400">Không xác định</span>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if($group->privacy == 'public')
                            <span class="bg-success/20 text-success rounded px-2 py-1">Công khai</span>
                        @elseif($group->privacy == 'private')
                            <span class="bg-primary/20 text-primary rounded px-2 py-1">Riêng tư</span>
                        @else
                            <span class="bg-danger/20 text-danger rounded px-2 py-1">Ẩn</span>
                        @endif
                    </td>
                    <td class="w-40">
                        <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                            <input id="status-switch-{{ $group->id }}" class="form-check-input status-toggle" name="toogle" value="{{ $group->id }}" type="checkbox" {{ $group->status == 'active' ? 'checked' : '' }} data-id="{{ $group->id }}">
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="{{ route('admin.community.groups.edit', $group->id) }}">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $group->id }}">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                            </a>
                        </div>

                        <!-- BEGIN: Delete Confirmation Modal -->
                        <div id="delete-confirmation-modal-{{ $group->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Bạn có chắc không?</div>
                                            <div class="text-slate-500 mt-2">Bạn thực sự muốn xóa nhóm này? <br>Thao tác này không thể hoàn tác.</div>
                                        </div>
                                        <div class="px-5 pb-8 text-center">
                                            <form action="{{ route('admin.community.groups.destroy', $group->id) }}" method="post" style="display: inline-block;">
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
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        {{ $groups->links() }}
    </div>
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
                url: "{{ route('admin.community.groups.status') }}",
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