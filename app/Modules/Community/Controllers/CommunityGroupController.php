<?php

namespace App\Modules\Community\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Community\Models\CommunityGroup;
use App\Modules\Community\Models\CommunityMember;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommunityGroupController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 10);
        $this->middleware('auth');

        $admin = User::find(1);
        if ($admin && $admin->name == 'Admin') {
            $admin->name = 'Quản trị viên';
            $admin->save();
            Log::info('Đã đổi tên người dùng admin thành Quản trị viên');
        }
    }

    public function index()
    {
        $adminExists = User::where('id', 1)->exists();
        if (!$adminExists) {
            User::create([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_admin' => 1
            ]);
            Log::info('Đã tự động tạo tài khoản admin mặc định');
        }

        $adminUser = User::find(1);
        Log::info('Admin user info: ', [
            'exists' => $adminUser ? 'Yes' : 'No',
            'name' => $adminUser ? $adminUser->name : 'N/A',
            'email' => $adminUser ? $adminUser->email : 'N/A'
        ]);

        $groupsWithoutCreator = CommunityGroup::whereNull('created_by')
            ->orWhere('created_by', 0)
            ->orWhereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('users')
                      ->whereRaw('users.id = community_groups.created_by');
            })
            ->get();

        Log::info('Số nhóm không có người tạo: ' . $groupsWithoutCreator->count());

        foreach ($groupsWithoutCreator as $group) {
            $admin = CommunityMember::where('group_id', $group->id)
                ->where('role', 'admin')
                ->first();

            $oldCreatedBy = $group->created_by;
            $group->created_by = $admin ? $admin->user_id : 1;
            $group->save();

            Log::info('Cập nhật nhóm ID: ' . $group->id . ', created_by từ ' . $oldCreatedBy . ' thành ' . $group->created_by);
        }

        $allGroups = CommunityGroup::all();
        foreach ($allGroups as $group) {
            $user = User::find($group->created_by);
            Log::info('Nhóm ID: ' . $group->id . ', created_by: ' . $group->created_by . ', user exists: ' . ($user ? 'Yes' : 'No'));
        }

        $updatedGroups = DB::update("
            UPDATE community_groups
            SET created_by = 1
            WHERE created_by IS NULL 
               OR created_by = 0
               OR NOT EXISTS (SELECT 1 FROM users WHERE id = community_groups.created_by)
        ");

        if ($updatedGroups > 0) {
            Log::info('Đã tự động cập nhật ' . $updatedGroups . ' nhóm có người tạo không hợp lệ');
        }

        $active_menu = "community_groups";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách nhóm cộng đồng</li>';

        // Log truy vấn để kiểm tra
        $query = CommunityGroup::activeUserGroups()->with('creator');
        Log::info('SQL Query in index: ' . $query->toSql(), $query->getBindings());

        $groups = $query->paginate($this->pagesize);

        return view('Community::group.index', compact('groups', 'active_menu', 'breadcrumb'));
    }

    // Các phương thức khác giữ nguyên
    public function create()
    {
        $active_menu = "community_group_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.index') . '">Danh sách nhóm cộng đồng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo nhóm mới</li>';
        
        return view('Community::group.create', compact('active_menu', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'privacy' => 'required|in:public,private,hidden',
            'status' => 'required|in:active,inactive',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tạo nhóm.');
        }

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back()->with('error', 'Không thể xác định người dùng. Vui lòng đăng nhập lại.');
        }

        $data = $request->except(['_token', 'cover_image']);
        
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('uploads/community/covers', 'public');
            $data['cover_image'] = $coverPath;
        }
        
        $group = new CommunityGroup();
        $group->name = $request->name;
        $group->slug = Str::slug($request->name);
        $group->description = $request->description;
        $group->cover_image = $data['cover_image'] ?? null;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        $group->created_by = $userId;
        
        $group->save();

        CommunityMember::create([
            'group_id' => $group->id,
            'user_id' => $userId,
            'role' => 'admin',
            'status' => 'active'
        ]);

        return redirect()->route('admin.community.groups.index')
            ->with('success', 'Nhóm cộng đồng đã được tạo thành công.');
    }

    public function show($id)
    {
        $active_menu = "community_groups";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.index') . '">Danh sách nhóm cộng đồng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết nhóm</li>';
        
        $group = CommunityGroup::with(['creator', 'members', 'posts.user'])->findOrFail($id);
        
        return view('Community::group.show', compact('group', 'active_menu', 'breadcrumb'));
    }

    public function edit($id)
    {
        $active_menu = "community_groups";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.index') . '">Danh sách nhóm cộng đồng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa nhóm</li>';
        
        $group = CommunityGroup::findOrFail($id);
        
        return view('Community::group.edit', compact('group', 'active_menu', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'privacy' => 'required|in:public,private,hidden',
            'status' => 'required|in:active,inactive',
        ]);

        $group = CommunityGroup::findOrFail($id);
        
        if ($group->name !== $request->name) {
            $group->slug = Str::slug($request->name);
        }
        
        $group->name = $request->name;
        $group->description = $request->description;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        
        if ($request->hasFile('cover_image')) {
            if ($group->cover_image) {
                Storage::disk('public')->delete($group->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('uploads/community/covers', 'public');
            $group->cover_image = $coverPath;
        }
        
        $group->save();

        return redirect()->route('admin.community.groups.index')
            ->with('success', 'Nhóm cộng đồng đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $group = CommunityGroup::findOrFail($id);
        $group->delete();

        return redirect()->route('admin.community.groups.index')
            ->with('success', 'Nhóm cộng đồng đã được xóa thành công.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:community_groups,id',
            'mode' => 'required|boolean',
        ]);

        $group = CommunityGroup::findOrFail($request->id);
        $group->status = $request->mode ? 'active' : 'inactive';
        $group->save();

        return response()->json(['msg' => 'Cập nhật trạng thái thành công']);
    }
}