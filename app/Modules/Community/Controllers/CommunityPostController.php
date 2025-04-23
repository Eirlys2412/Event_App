<?php

namespace App\Modules\Community\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Community\Models\CommunityPost;
use App\Modules\Community\Models\CommunityGroup;
use App\Modules\Community\Models\CommunityMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Thêm dòng này

class CommunityPostController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '10');
        $this->middleware('auth');
    }

    // Hiển thị danh sách bài đăng
    public function index()
    {
        $active_menu = "community_posts";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách bài đăng cộng đồng</li>';
        
        $posts = CommunityPost::with(['user', 'group'])->paginate($this->pagesize);
        
        return view('Community::post.index', compact('posts', 'active_menu', 'breadcrumb'));
    }

    // Hiển thị form tạo bài đăng mới
    public function create()
    {
        $userId = Auth::id();
        $groups = CommunityGroup::whereExists(function ($query) use ($userId) {
            $query->select(\DB::raw(1))
                  ->from('users')
                  ->join('community_members', 'users.id', '=', 'community_members.user_id')
                  ->whereColumn('community_groups.id', 'community_members.group_id')
                  ->where('community_members.user_id', $userId)
                  ->whereIn('community_members.role', ['admin', 'moderator', 'member'])
                  ->where('community_members.status', 'active');
        })->where('community_groups.status', 'active')->get();

        // Log để kiểm tra
        Log::info('SQL Query in CommunityPostController::create: ' . $groups->toQuery()->toSql(), $groups->toQuery()->getBindings());

        $active_menu = "community_posts";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo bài viết cộng đồng</li>';

        return view('Community::post.create', compact('groups', 'active_menu', 'breadcrumb'));
    }
    // Lưu bài đăng mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'group_id' => 'required|exists:community_groups,id',
            'media' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Kiểm tra xem người dùng có quyền đăng bài trong nhóm hay không
        $group_id = $request->group_id;
        $user_id = Auth::id();
        
        $member = CommunityMember::where('group_id', $group_id)
                                 ->where('user_id', $user_id)
                                 ->where('status', 'active')
                                 ->first();
        
        if (!$member) {
            return redirect()->back()->with('error', 'Bạn không có quyền đăng bài trong nhóm này.');
        }

        // Xử lý media (nếu có)
        $media = null;
        if ($request->has('media') && !empty($request->media)) {
            $media = explode(',', $request->media);
        }

        $post = new CommunityPost();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->group_id = $group_id;
        $post->user_id = $user_id;
        $post->media = $media;
        $post->status = $request->status;
        $post->save();

        return redirect()->route('admin.community.posts.index')
            ->with('success', 'Bài đăng đã được tạo thành công.');
    }

    // Hiển thị chi tiết bài đăng
    public function show($id)
    {
        $active_menu = "community_posts";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.posts.index') . '">Danh sách bài đăng cộng đồng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết bài đăng</li>';
        
        $post = CommunityPost::with(['user', 'group'])->findOrFail($id);
        
        return view('Community::post.show', compact('post', 'active_menu', 'breadcrumb'));
    }

    // Hiển thị form chỉnh sửa bài đăng
    public function edit($id)
    {
        $userId = Auth::id();
        $post = CommunityPost::findOrFail($id);

        $groups = CommunityGroup::whereExists(function ($query) use ($userId) {
            $query->select(\DB::raw(1))
                  ->from('users')
                  ->join('community_members', 'users.id', '=', 'community_members.user_id')
                  ->whereColumn('community_groups.id', 'community_members.group_id')
                  ->where('community_members.user_id', $userId)
                  ->whereIn('community_members.role', ['admin', 'moderator', 'member'])
                  ->where('community_members.status', 'active');
        })->where('community_groups.status', 'active')->get();

        Log::info('SQL Query in CommunityPostController::edit: ' . $groups->toQuery()->toSql(), $groups->toQuery()->getBindings());

        if (!$groups->contains($post->group_id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $active_menu = "community_posts";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa bài viết cộng đồng</li>';

        return view('Community::post.edit', compact('post', 'groups', 'active_menu', 'breadcrumb'));
    }
    // Cập nhật bài đăng
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'group_id' => 'required|exists:community_groups,id',
            'media' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $post = CommunityPost::findOrFail($id);
        
        // Kiểm tra quyền chỉnh sửa
        $user_id = Auth::id();
        if ($post->user_id != $user_id) {
            $member = CommunityMember::where('group_id', $post->group_id)
                                    ->where('user_id', $user_id)
                                    ->whereIn('role', ['admin', 'moderator'])
                                    ->first();
            
            if (!$member) {
                return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bài đăng này.');
            }
        }

        // Xử lý media (nếu có)
        $media = null;
        if ($request->has('media') && !empty($request->media)) {
            $media = explode(',', $request->media);
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->group_id = $request->group_id;
        $post->media = $media;
        $post->status = $request->status;
        $post->save();

        return redirect()->route('admin.community.posts.index')
            ->with('success', 'Bài đăng đã được cập nhật thành công.');
    }

    // Xóa bài đăng
    public function destroy($id)
    {
        $post = CommunityPost::findOrFail($id);
        
        // Kiểm tra quyền xóa
        $user_id = Auth::id();
        if ($post->user_id != $user_id) {
            $member = CommunityMember::where('group_id', $post->group_id)
                                    ->where('user_id', $user_id)
                                    ->whereIn('role', ['admin', 'moderator'])
                                    ->first();
            
            if (!$member) {
                return redirect()->back()->with('error', 'Bạn không có quyền xóa bài đăng này.');
            }
        }
        
        $post->delete();

        return redirect()->route('admin.community.posts.index')
            ->with('success', 'Bài đăng đã được xóa thành công.');
    }

    // Cập nhật trạng thái bài đăng
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:community_posts,id',
            'mode' => 'required|boolean',
        ]);

        $post = CommunityPost::findOrFail($request->id);
        
        // Kiểm tra quyền cập nhật trạng thái
        $user_id = Auth::id();
        if ($post->user_id != $user_id) {
            $member = CommunityMember::where('group_id', $post->group_id)
                                    ->where('user_id', $user_id)
                                    ->whereIn('role', ['admin', 'moderator'])
                                    ->first();
            
            if (!$member) {
                return response()->json(['error' => 'Bạn không có quyền cập nhật trạng thái bài đăng này.'], 403);
            }
        }
        
        $post->status = $request->mode ? 'active' : 'inactive';
        $post->save();

        return response()->json(['msg' => 'Cập nhật trạng thái thành công']);
    }

    // Hiển thị danh sách bài đăng trong một nhóm cụ thể
    public function indexByGroup($group_id)
    {
        $group = CommunityGroup::findOrFail($group_id);
        
        $active_menu = "community_groups";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.index') . '">Danh sách nhóm cộng đồng</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.community.groups.show', $group_id) . '">' . $group->name . '</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bài đăng trong nhóm</li>';

        $posts = CommunityPost::where('group_id', $group_id)->with('user')->latest()->paginate($this->pagesize);
        
        return view('Community::post.index', compact('posts', 'group', 'active_menu', 'breadcrumb'));
    }
} 