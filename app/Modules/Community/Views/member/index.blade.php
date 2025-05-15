@extends('backend.layouts.master')
@section('content')

<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Quản lý thành viên nhóm: {{ $group->name }}</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('admin.community.groups.show', $group->id) }}" class="btn btn-outline-secondary shadow-md mr-2">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Quay lại chi tiết nhóm
        </a>
        <button class="btn btn-primary shadow-md" data-tw-toggle="modal" data-tw-target="#add-member-modal">
            <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i> Thêm thành viên
        </button>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Thống kê thành viên -->
    <div class="col-span-12 intro-y">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="box p-5 zoom-in">
                <div class="flex items-center">
                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-primary">
                        <i data-lucide="users" class="w-6 h-6 text-white m-auto"></i>
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">Tổng thành viên</div>
                        <div class="text-slate-500 text-xs mt-0.5">Bao gồm tất cả vai trò</div>
                    </div>
                    <div class="text-2xl font-medium">{{ $members->count() }}</div>
                </div>
            </div>
            <div class="box p-5 zoom-in">
                <div class="flex items-center">
                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-success">
                        <i data-lucide="shield" class="w-6 h-6 text-white m-auto"></i>
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">Quản trị viên</div>
                        <div class="text-slate-500 text-xs mt-0.5">Có quyền quản trị nhóm</div>
                    </div>
                    <div class="text-2xl font-medium">{{ $members->where('role', 'admin')->count() }}</div>
                </div>
            </div>
            <div class="box p-5 zoom-in">
                <div class="flex items-center">
                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-warning">
                        <i data-lucide="user-check" class="w-6 h-6 text-white m-auto"></i>
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">Thành viên hoạt động</div>
                        <div class="text-slate-500 text-xs mt-0.5">Đã được duyệt</div>
                    </div>
                    <div class="text-2xl font-medium">{{ $members->where('status', 'active')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng thành viên -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="box p-5">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-200">
                <h3 class="font-medium text-base">Danh sách thành viên</h3>
                <div class="flex">
                    <div class="w-56 relative text-slate-500 mr-2">
                        <input type="text" class="form-control w-56 box pr-10" placeholder="Tìm kiếm...">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                    </div>
                    <select class="form-select box w-40">
                        <option value="all">Tất cả vai trò</option>
                        <option value="admin">Quản trị viên</option>
                        <option value="moderator">Điều hành viên</option>
                        <option value="member">Thành viên</option>
                    </select>
                </div>
            </div>

            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">THÀNH VIÊN</th>
                        <th class="whitespace-nowrap">LIÊN HỆ</th>
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
                                    <img class="rounded-full" src="{{ $member->user->avatar ? asset('storage/' . $member->user->avatar) : asset('backend/images/profile-1.jpg') }}" alt="{{ $member->user->full_name ?? $member->user->username }}">
                                </div>
                                <div>
                                    <div class="font-medium">{{ $member->user->full_name ?? $member->user->username }}</div>
                                    <div class="text-slate-500 text-xs">ID: {{ $member->user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-slate-500">
                                <div><i data-lucide="mail" class="w-3.5 h-3.5 mr-1 inline-block"></i> {{ $member->user->email }}</div>
                                @if($member->user->phone)
                                <div class="mt-1"><i data-lucide="phone" class="w-3.5 h-3.5 mr-1 inline-block"></i> {{ $member->user->phone }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="role-badge 
                                    @if($member->role == 'admin') bg-danger/20 text-danger
                                    @elseif($member->role == 'moderator') bg-warning/20 text-warning
                                    @else bg-primary/20 text-primary @endif
                                    rounded px-2 py-1 mb-2">
                                    @if($member->role == 'admin') Admin
                                    @elseif($member->role == 'moderator') Điều hành viên
                                    @else Thành viên
                                    @endif
                                </span>
                                <select class="form-select w-full sm:w-32 member-role-change" data-id="{{ $member->id }}" data-original-role="{{ $member->role }}">
                                    <option value="admin" @if($member->role == 'admin') selected @endif>Admin</option>
                                    <option value="moderator" @if($member->role == 'moderator') selected @endif>Điều hành viên</option>
                                    <option value="member" @if($member->role == 'member') selected @endif>Thành viên</option>
                                </select>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input member-status-toggle" {{ $member->status == 'active' ? 'checked' : '' }} data-id="{{ $member->id }}">
                                    <span class="text-xs ml-2 {{ $member->status == 'active' ? 'text-success' : 'text-danger' }}">
                                        {{ $member->status == 'active' ? 'Hoạt động' : ($member->status == 'pending' ? 'Chờ duyệt' : 'Đã chặn') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $member->created_at->format('d/m/Y') }}</td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <div class="dropdown">
                                    <button class="dropdown-toggle btn btn-sm btn-outline-secondary" aria-expanded="false" data-tw-toggle="dropdown">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                    <div class="dropdown-menu w-40">
                                        <ul class="dropdown-content">
                                            <li>
                                                <a href="javascript:;" class="dropdown-item">
                                                    <i data-lucide="user" class="w-4 h-4 mr-2"></i> Thông tin
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" class="dropdown-item view-posts" data-id="{{ $member->user_id }}">
                                                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Xem bài viết
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $member->id }}" class="dropdown-item text-danger">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Xóa thành viên
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- BEGIN: Delete Confirmation Modal -->
                    <div id="delete-confirmation-modal-{{ $member->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Bạn chắc chắn muốn xóa?</div>
                                        <div class="text-slate-500 mt-2">
                                            Bạn có thực sự muốn xóa thành viên <span class="font-medium">{{ $member->user->full_name ?? $member->user->username }}</span> khỏi nhóm?<br>
                                            Thao tác này không thể hoàn tác.
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <form action="{{ route('admin.community.members.destroy', [$group->id, $member->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Hủy</button>
                                            <button type="submit" class="btn btn-danger w-24">Xóa</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Delete Confirmation Modal -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
                        <select id="user_id" name="user_id" class="tom-select w-full" data-placeholder="Tìm kiếm người dùng...">
                            <option value="">-- Chọn người dùng --</option>
                            @php
                                $nonMembers = \App\Models\User::whereNotIn('id', $members->pluck('user_id'))->get();
                            @endphp
                            @foreach($nonMembers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->full_name ?? $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-12">
                        <label for="role" class="form-label">Vai trò trong nhóm</label>
                        <select id="role" name="role" class="form-select">
                            <option value="member">Thành viên</option>
                            <option value="moderator">Điều hành viên</option>
                            <option value="admin">Quản trị viên</option>
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

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Xử lý thay đổi vai trò
        $('.member-role-change').on('change', function() {
            const select = $(this);
            const id = select.data('id');
            const role = select.val();
            const memberName = select.closest('tr').find('.font-medium').first().text();
            const originalRole = select.attr('data-original-role');
            
            let roleText = '';
            if (role === 'admin') roleText = 'Quản trị viên';
            else if (role === 'moderator') roleText = 'Điều hành viên';
            else roleText = 'Thành viên';
            
            Swal.fire({
                title: 'Xác nhận thay đổi vai trò',
                html: `Bạn có chắc muốn đổi vai trò của <strong>${memberName}</strong> thành <strong>${roleText}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng đợi trong giây lát',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Gửi AJAX request khi người dùng xác nhận
                    $.ajax({
                        url: "{{ route('admin.community.members.role') }}",
                        type: "POST",
                        data: {
                            id: id,
                            role: role,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Đóng loading
                            Swal.close();
                            
                            if (response.msg) {
                                // Hiển thị thông báo thành công và tải lại trang
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: response.msg,
                                    allowOutsideClick: false,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    // Luôn luôn tải lại trang sau khi thay đổi vai trò
                                    // để đảm bảo UI đồng bộ với DB
                                    window.location.href = window.location.href + '?_=' + new Date().getTime();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Đóng loading
                            Swal.close();
                            
                            let errorMsg = 'Có lỗi xảy ra, vui lòng thử lại sau';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            
                            // Reset select về giá trị ban đầu
                            select.val(originalRole);
                            
                            // Hiển thị lỗi
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: errorMsg
                            });
                        }
                    });
                } else {
                    // Người dùng hủy, reset về giá trị ban đầu
                    select.val(originalRole);
                }
            });
        });
        
        // Lưu giá trị ban đầu khi trang tải
        $('.member-role-change').each(function() {
            $(this).attr('data-original-role', $(this).val());
        });
        
        // Xử lý thay đổi trạng thái
        $('.member-status-toggle').on('change', function() {
            const checkbox = $(this);
            const id = checkbox.data('id');
            const status = checkbox.prop('checked') ? 'active' : 'blocked';
            const memberName = checkbox.closest('tr').find('.font-medium').first().text();
            
            let statusText = status === 'active' ? 'kích hoạt' : 'chặn';
            
            Swal.fire({
                title: 'Xác nhận thay đổi trạng thái',
                html: `Bạn có chắc muốn <strong>${statusText}</strong> thành viên <strong>${memberName}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.community.members.status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.msg) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMsg = 'Có lỗi xảy ra, vui lòng thử lại sau';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: errorMsg
                            });
                            
                            // Reset checkbox về trạng thái ban đầu
                            checkbox.prop('checked', !checkbox.prop('checked'));
                        }
                    });
                } else {
                    // Nếu người dùng hủy, reset checkbox về trạng thái ban đầu
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            });
        });
        
        // Xử lý cập nhật role hệ thống
        $('.member-system-role-change').on('change', function() {
            const select = $(this);
            const id = select.data('id');
            const role_id = select.val();
            const memberName = select.closest('tr').find('.font-medium').first().text();
            const originalRoleId = select.data('original-system-role') || select.find('option[selected]').val() || '';
            const roleText = role_id ? select.find('option:selected').text() : "Không có role";
            
            Swal.fire({
                title: 'Xác nhận thay đổi role hệ thống',
                html: `Bạn có chắc muốn đổi role hệ thống của <strong>${memberName}</strong> thành <strong>${roleText}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.community.members.system-role') }}",
                        type: "POST",
                        data: {
                            id: id,
                            role_id: role_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.msg) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // Lưu vai trò hiện tại làm giá trị gốc
                                select.data('original-system-role', role_id);
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMsg = 'Có lỗi xảy ra, vui lòng thử lại sau';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: errorMsg
                            });
                            
                            // Reset select box về giá trị ban đầu
                            select.val(originalRoleId);
                        }
                    });
                } else {
                    // Nếu người dùng hủy, reset select box về giá trị ban đầu
                    select.val(originalRoleId);
                }
            });
        });
        
        // Lưu giá trị ban đầu của mỗi select role hệ thống
        $('.member-system-role-change').each(function() {
            $(this).data('original-system-role', $(this).val());
        });
    });
</script>
@endsection 