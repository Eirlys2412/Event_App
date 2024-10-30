<?php

namespace App\Modules\Group\Controllers;
use App\Models\User;
use App\Modules\Group\Models\GroupMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use App\Modules\Group\Models\Group; 
use Illuminate\Support\Facades\Storage;
class GroupMemberController extends Controller
{
    protected $pagesize; // Biến để lưu kích thước trang

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('admin.auth');
    }
    public function index($groupId)
    {
        $func = "group_member_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized'); // Redirect đến trang không có quyền nếu không đủ quyền
        }

        // Thiết lập menu hoạt động và breadcrumb
        $active_menu = "group_member_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách thành viên nhóm</li>';

        // Lấy danh sách thành viên của nhóm cụ thể và phân trang theo kích thước đã định
        $groupMembers = GroupMember::where('group_id', $groupId)->orderBy('id', 'DESC')->paginate(10);

        // Trả về view với dữ liệu cần thiết
        return view('Group::groupmember.index', compact('groupMembers', 'breadcrumb', 'active_menu', 'groupId'));
    }

    // Hiển thị form tạo thành viên mới
    public function create($groupId)
    {
        $active_menu = "group_add"; // Đặt giá trị cho active_menu
        $users = User::all(); // Lấy tất cả người dùng
        return view('Group::groupmember.create', compact('groupId', 'active_menu', 'users')); // Truyền biến $users vào view
    }
    // Lưu thành viên mới

public function store(Request $request, $groupId)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'user_id' => 'required|exists:users,id', // Đảm bảo user_id là bắt buộc
        'role' => 'required|in:member,admin,lecturer', // Xác thực vai trò
        'status' => 'required|in:active,inactive', // Xác thực trạng thái
    ]);

    // Tạo thành viên mới
    GroupMember::create([
        'group_id' => $groupId,
        'user_id' => $request->user_id, // Lưu user_id
        'role' => $request->role, // Lưu vai trò
        'status' => $request->status, // Lưu trạng thái
    ]);

    return redirect()->route('admin.groupmember.index', $groupId)
                     ->with('success', 'Thành viên đã được thêm thành công.');
}

    // Hiển thị form chỉnh sửa thành viên
    public function edit($groupId, $id)
    {
        $active_menu = "group_edit"; // Đặt giá trị cho active_menu
        $groupMember = GroupMember::findOrFail($id); // Lấy thông tin thành viên
        $users = User::all(); // Lấy danh sách người dùng
    
        return view('Group::groupmember.edit', compact('groupMember', 'groupId', 'users', 'active_menu'));
    }

    // Cập nhật thông tin thành viên
    public function update(Request $request, $groupId, $id)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'role' => 'required|string|in:admin,member',
        'status' => 'required|in:active,inactive',
    ]);

    $member = GroupMember::findOrFail($id);
    $member->update([
        'full_name' => $request->full_name,
        'role' => $request->role,
        'status' => $request->status,
    ]);

    return redirect()->route('admin.groupmember.index', $groupId)->with('success', 'Thông tin thành viên đã được cập nhật.');
}

    // Xóa thành viên
    public function destroy($id)
    {
        $member = GroupMember::findOrFail($id);
        $groupId = $member->group_id;
        $member->delete();

        return redirect()->route('admin.groupmember.index', $groupId)->with('success', 'Thành viên đã được xóa.');
    }
}