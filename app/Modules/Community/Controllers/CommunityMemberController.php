<?php

namespace App\Modules\Community\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Community\Models\CommunityMember;
use App\Modules\Community\Models\CommunityGroup;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommunityMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Hiển thị danh sách thành viên của một nhóm
    public function index($group_id)
    {
        $group = CommunityGroup::findOrFail($group_id);
        
        $active_menu = "community_groups";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.index') . '">Danh sách nhóm cộng đồng</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.show', $group_id) . '">' . $group->name . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý thành viên</li>';
        
        $members = CommunityMember::where('group_id', $group_id)
                                ->with(['user', 'role_info'])
                                ->get();
        
        // Lấy danh sách roles từ hệ thống
        $roles = \App\Models\Role::where('status', 'active')->get();
        
        // Lấy danh sách người dùng không phải là thành viên của nhóm
        $nonMembers = \App\Models\User::whereNotIn('id', $members->pluck('user_id'))->get();
        
        return view('Community::member.index', compact('group', 'members', 'roles', 'nonMembers', 'active_menu', 'breadcrumb'));
    }

    // Thêm thành viên mới vào nhóm
    public function store(Request $request, $group_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,moderator,member',
        ]);
        
        // Kiểm tra quyền thêm thành viên
        $group = CommunityGroup::findOrFail($group_id);
        $auth_user_id = Auth::id();
        $userMember = CommunityMember::where('group_id', $group_id)
                                    ->where('user_id', $auth_user_id)
                                    ->whereIn('role', ['admin', 'moderator'])
                                    ->first();
        
        if (!$userMember) {
            return redirect()->back()->with('error', 'Bạn không có quyền thêm thành viên vào nhóm này.');
        }
        
        // Kiểm tra xem người dùng đã là thành viên chưa
        $existingMember = CommunityMember::where('group_id', $group_id)
                                        ->where('user_id', $request->user_id)
                                        ->first();
        
        if ($existingMember) {
            return redirect()->back()->with('error', 'Người dùng này đã là thành viên của nhóm.');
        }
        
        // Tạo thành viên mới - không còn role_id nữa
        $member = new CommunityMember();
        $member->group_id = $group_id;
        $member->user_id = $request->user_id;
        $member->role = $request->role;
        $member->status = 'active';
        $member->save();
        
        return redirect()->route('admin.community.members.index', $group_id)
                        ->with('success', 'Thêm thành viên thành công.');
    }

    // Cập nhật vai trò hoặc trạng thái của thành viên
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'sometimes|required|in:admin,moderator,member',
            'status' => 'sometimes|required|in:active,pending,blocked',
        ]);
        
        $member = CommunityMember::findOrFail($id);
        $group_id = $member->group_id;
        
        // Kiểm tra quyền cập nhật thành viên
        $auth_user_id = Auth::id();
        $userMember = CommunityMember::where('group_id', $group_id)
                                    ->where('user_id', $auth_user_id)
                                    ->where('role', 'admin')
                                    ->first();
        
        if (!$userMember) {
            return redirect()->back()->with('error', 'Bạn không có quyền cập nhật thành viên của nhóm này.');
        }
        
        // Không cho phép admin cuối cùng bị hạ cấp
        if ($member->role == 'admin' && $request->has('role') && $request->role != 'admin') {
            $adminCount = CommunityMember::where('group_id', $group_id)
                                        ->where('role', 'admin')
                                        ->count();
            
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Không thể hạ cấp admin duy nhất của nhóm.');
            }
        }
        
        if ($request->has('role')) {
            $member->role = $request->role;
        }
        
        if ($request->has('status')) {
            $member->status = $request->status;
        }
        
        $member->save();
        
        return redirect()->route('admin.community.members.index', $group_id)
                        ->with('success', 'Cập nhật thành viên thành công.');
    }

    // Xóa thành viên ra khỏi nhóm
    public function destroy($id)
    {
        $member = CommunityMember::findOrFail($id);
        $group_id = $member->group_id;
        
        // Kiểm tra quyền xóa thành viên
        $auth_user_id = Auth::id();
        $userMember = CommunityMember::where('group_id', $group_id)
                                    ->where('user_id', $auth_user_id)
                                    ->whereIn('role', ['admin', 'moderator'])
                                    ->first();
        
        if (!$userMember && $auth_user_id != $member->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa thành viên ra khỏi nhóm này.');
        }
        
        // Không cho phép xóa admin cuối cùng
        if ($member->role == 'admin') {
            $adminCount = CommunityMember::where('group_id', $group_id)
                                        ->where('role', 'admin')
                                        ->count();
            
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Không thể xóa admin duy nhất của nhóm.');
            }
        }
        
        $member->delete();
        
        return redirect()->route('admin.community.members.index', $group_id)
                        ->with('success', 'Xóa thành viên thành công.');
    }

    // Người dùng xin tham gia nhóm
    public function join($group_id)
    {
        $group = CommunityGroup::findOrFail($group_id);
        $user_id = Auth::id();
        
        // Kiểm tra xem người dùng đã là thành viên chưa
        $existingMember = CommunityMember::where('group_id', $group_id)
                                         ->where('user_id', $user_id)
                                         ->first();
        
        if ($existingMember) {
            return redirect()->back()->with('error', 'Bạn đã là thành viên hoặc đã gửi yêu cầu tham gia nhóm này.');
        }
        
        $member = new CommunityMember();
        $member->group_id = $group_id;
        $member->user_id = $user_id;
        $member->role = 'member';
        
        // Nếu nhóm là public, tự động chấp nhận
        if ($group->privacy == 'public') {
            $member->status = 'active';
        } else {
            $member->status = 'pending';
        }
        
        $member->save();

        return redirect()->back()->with('success', 'Yêu cầu tham gia nhóm đã được gửi.');
    }

    // Người dùng rời khỏi nhóm
    public function leave($group_id)
    {
        $user_id = Auth::id();
        $member = CommunityMember::where('group_id', $group_id)
                                ->where('user_id', $user_id)
                                ->first();
        
        if (!$member) {
            return redirect()->back()->with('error', 'Bạn không phải là thành viên của nhóm này.');
        }
        
        // Kiểm tra xem người dùng có phải là admin duy nhất không
        if ($member->role == 'admin') {
            $adminCount = CommunityMember::where('group_id', $group_id)
                                       ->where('role', 'admin')
                                       ->count();
            
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Bạn không thể rời khỏi nhóm vì bạn là admin duy nhất. Hãy chỉ định admin khác trước khi rời khỏi.');
            }
        }
        
        $member->delete();
        
        return redirect()->route('admin.community.groups.index')
                        ->with('success', 'Bạn đã rời khỏi nhóm thành công.');
    }

    // Cập nhật role hệ thống cho thành viên
    public function updateSystemRole(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:community_members,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $member = CommunityMember::findOrFail($request->id);
        
        // Kiểm tra quyền
        $user_id = Auth::id();
        $currentMember = CommunityMember::where('group_id', $member->group_id)
                                      ->where('user_id', $user_id)
                                      ->where('role', 'admin')
                                      ->first();
        
        if (!$currentMember) {
            return response()->json(['error' => 'Bạn không có quyền thay đổi role hệ thống của thành viên này.'], 403);
        }
        
        $member->role_id = $request->role_id;
        $member->save();

        return response()->json(['msg' => 'Cập nhật role hệ thống thành công']);
    }

    // Cập nhật vai trò của thành viên
    public function updateRole(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:community_members,id',
            'role' => 'required|in:admin,moderator,member',
        ]);

        $member = CommunityMember::findOrFail($request->id);
        
        // Kiểm tra quyền
        $user_id = Auth::id();
        $currentMember = CommunityMember::where('group_id', $member->group_id)
                                      ->where('user_id', $user_id)
                                      ->first();
        
        if (!$currentMember || $currentMember->role != 'admin') {
            return response()->json(['error' => 'Bạn không có quyền thay đổi vai trò của thành viên này.'], 403);
        }
        
        // Không cho phép xóa admin cuối cùng
        if ($member->role == 'admin' && $request->role != 'admin') {
            $adminCount = CommunityMember::where('group_id', $member->group_id)
                                       ->where('role', 'admin')
                                       ->count();
            
            if ($adminCount <= 1) {
                return response()->json(['error' => 'Không thể thay đổi vai trò của admin duy nhất.'], 400);
            }
        }
        
        $member->role = $request->role;
        $member->save();

        return response()->json([
            'msg' => 'Cập nhật vai trò thành công', 
            'role' => $member->role // Trả về vai trò đã lưu để đảm bảo UI đồng bộ
        ]);
    }

    // Cập nhật trạng thái của thành viên
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:community_members,id',
            'status' => 'required|in:active,inactive,blocked,pending',
        ]);

        $member = CommunityMember::findOrFail($request->id);
        
        // Kiểm tra quyền
        $user_id = Auth::id();
        $currentMember = CommunityMember::where('group_id', $member->group_id)
                                     ->where('user_id', $user_id)
                                     ->whereIn('role', ['admin', 'moderator'])
                                     ->first();
        
        if (!$currentMember) {
            return response()->json(['error' => 'Bạn không có quyền thay đổi trạng thái của thành viên này.'], 403);
        }
        
        // Không cho phép thay đổi trạng thái của admin
        if ($member->role == 'admin' && $currentMember->role != 'admin') {
            return response()->json(['error' => 'Bạn không có quyền thay đổi trạng thái của admin.'], 403);
        }
        
        $member->status = $request->status;
        $member->save();

        return response()->json(['msg' => 'Cập nhật trạng thái thành công']);
    }
} 