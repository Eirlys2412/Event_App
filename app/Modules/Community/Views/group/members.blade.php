@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thành viên nhóm: {{ $group->name }}</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('admin.community.groups.show', $group->id) }}" class="btn btn-outline-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Quay lại
        </a>
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-member-modal">
            <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Thêm thành viên
        </button>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">THÀNH VIÊN</th>
                    <th class="text-center whitespace-nowrap">VAI TRÒ</th>
                    <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                    <th class="text-center whitespace-nowrap">NGÀY THAM GIA</th>
                    <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr class="intro-x">
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 image-fit zoom-in mr-3">
                                <img class="rounded-full" alt="{{ $member->user->name ?? 'Unknown' }}" src="{{ $member->user->avatar ?? asset('dist/images/profile-1.jpg') }}">
                            </div>
                            <div>
                                <div class="font-medium whitespace-nowrap">{{ $member->user->name ?? 'Unknown' }}</div>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $member->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($member->role == 'admin')
                            <span class="bg-danger/20 text-danger rounded px-2 py-1">Admin</span>
                        @elseif($member->role == 'moderator')
                            <span class="bg-warning/20 text-warning rounded px-2 py-1">Quản trị viên</span>
                        @else
                            <span class="bg-primary/20 text-primary rounded px-2 py-1">Thành viên</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($member->status == 'active')
                            <span class="bg-success/20 text-success rounded px-2 py-1">Đã kích hoạt</span>
                        @elseif($member->status == 'pending')
                            <span class="bg-pending/20 text-pending rounded px-2 py-1">Đang chờ</span>
                        @else
                            <span class="bg-danger/20 text-danger rounded px-2 py-1">Đã chặn</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $member->created_at->format('d/m/Y H:i') }}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a href="javascript:;" class="flex items-center mr-3" data-tw-toggle="modal" data-tw-target="#edit-member-modal-{{ $member->id }}">
                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Sửa
                            </a>
                            @if($member->user_id != Auth::id() || ($userMember && $userMember->role == 'admin'))
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $member->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Xóa
                                </a>
                            @endif
                        </div>

                        <!-- BEGIN: Edit Member Modal -->
                        <div id="edit-member-modal-{{ $member->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Cập nhật thành viên</h2>
                                    </div>
                                    <form action="{{ route('admin.community.members.update', $member->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12">
                                                <label for="role" class="form-label">Vai trò</label>
                                                <select id="role" name="role" class="form-select w-full">
                                                    <option value="admin" {{ $member->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="moderator" {{ $member->role == 'moderator' ? 'selected' : '' }}>Quản trị viên</option>
                                                    <option value="member" {{ $member->role == 'member' ? 'selected' : '' }}>Thành viên</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12">
                                                <label for="status" class="form-label">Trạng thái</label>
                                                <select id="status" name="status" class="form-select w-full">
                                                    <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Đã kích hoạt</option>
                                                    <option value="pending" {{ $member->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                                    <option value="blocked" {{ $member->status == 'blocked' ? 'selected' : '' }}>Đã chặn</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Hủy</button>
                                            <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- END: Edit Member Modal -->

                        <!-- BEGIN: Delete Confirmation Modal -->
                        <div id="delete-confirmation-modal-{{ $member->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Bạn có chắc không?</div>
                                            <div class="text-slate-500 mt-2">Bạn thực sự muốn xóa thành viên này khỏi nhóm? <br>Thao tác này không thể hoàn tác.</div>
                                        </div>
                                        <div class="px-5 pb-8 text-center">
                                            <form action="{{ route('admin.community.members.destroy', $member->id) }}" method="post" style="display: inline-block;">
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
        {{ $members->links() }}
    </div>
    <!-- END: Pagination -->
</div>

<!-- BEGIN: Add Member Modal -->
<div id="add-member-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Thêm thành viên mới</h2>
            </div>
            <form action="{{ route('admin.community.members.store', $group->id) }}" method="post">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="user_id" class="form-label">Chọn người dùng</label>
                        <select id="user_id" name="user_id" class="form-select w-full">
                            @foreach(App\Models\User::whereNotIn('id', $members->pluck('user_id'))->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-12">
                        <label for="role" class="form-label">Vai trò</label>
                        <select id="role" name="role" class="form-select w-full">
                            <option value="admin">Admin</option>
                            <option value="moderator">Quản trị viên</option>
                            <option value="member" selected>Thành viên</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Hủy</button>
                    <button type="submit" class="btn btn-primary w-24">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Member Modal -->

@endsection 