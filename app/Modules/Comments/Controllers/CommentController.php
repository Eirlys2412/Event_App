<?php

namespace App\Modules\Comments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Models\Blog;
use App\Modules\Comments\Models\Comment;

use App\Modules\Community\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $itemId = $request->query('item_id');
        $itemCode = $request->query('item_code');
    
        $query = Comment::with(['user', 'replies' => function ($query) {
            $query->with('user');
        }])->orderBy('created_at', 'asc');
    
        if ($itemId && $itemCode) {
            $query->where('item_id', $itemId)->where('item_code', $itemCode);
        }
    
        $comments = $query->paginate(10);
    
        // Truyền biến `$active_menu`
        $active_menu = 'comments';
    
        return view('Comments::comments.index', compact('comments', 'itemId', 'itemCode', 'active_menu'));
    }
    

public function search(Request $request)
{
    $itemId = $request->query('item_id');
    $itemCode = $request->query('item_code');
    $search = $request->query('datasearch');

    $comments = Comment::with(['user', 'replies']) // Đảm bảo tải user
        ->where('item_id', $itemId)
        ->where('item_code', $itemCode)
        ->whereNull('parent_id')
        ->where('content', 'like', "%{$search}%")
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $item = null;
    if ($itemCode === 'blog') {
        $item = Blog::find($itemId);
    } elseif ($itemCode === 'community_post') {
        $item = CommunityPost::find($itemId);
    }

    $active_menu = 'comments';

    return view('Comments::comments.index', compact('comments', 'itemId', 'itemCode', 'item', 'active_menu'));
}

    public function create(Request $request)
    {
        $itemId = $request->query('item_id');
        $itemCode = $request->query('item_code');
    
        // Lấy danh sách blogs và community posts để chọn
        $blogs = Blog::all(['id', 'title']);
        $communityPosts = \App\Modules\Community\Models\CommunityPost::all(['id', 'title']);
    
        // Lấy danh sách bình luận cha (nếu có item_id và item_code)
        $parentComments = [];
        if ($itemId && $itemCode) {
            $parentComments = Comment::where('item_id', $itemId)
                ->where('item_code', $itemCode)
                ->whereNull('parent_id')
                ->get(['id', 'content']);
        }
    
        $active_menu = 'comments';
    
        return view('Comments::comments.create', compact('itemId', 'itemCode', 'blogs', 'communityPosts', 'parentComments', 'active_menu'));
    }

    public function store(Request $request)
{
    $request->validate([
        'item_id' => 'required|integer',
        'item_type' => 'required|string|in:blog,community_post', // Đổi thành item_type
        'content' => 'required|string',
        'parent_id' => 'nullable|integer|exists:comments,id',
        'comment_resources' => 'nullable|file|mimes:jpeg,png,gif|max:2048',
    ]);

    $commentData = [
        'item_id' => $request->item_id,
        'item_code' => $request->item_type, // Lấy từ item_type
        'user_id' => Auth::id(),
        'content' => $request->content,
        'parent_id' => $request->parent_id ?: null,
    ];

    if ($request->hasFile('comment_resources')) {
        $path = $request->file('comment_resources')->store('comments', 'public');
        $commentData['comment_resources'] = $path;
    }

    $comment = Comment::create($commentData);

    return redirect()->route('admin.comments.index', [
        'item_id' => $request->item_id,
        'item_code' => $request->item_type
    ])->with('success', 'Bình luận đã được tạo thành công.');
}

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa.');
        }
        $active_menu = 'comments';
        return view('Comments::comments.edit', compact('comment', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa.');
        }

        $comment->update(['content' => $request->content]);
        return redirect()->route('admin.comments.index', [
            'item_id' => $comment->item_id,
            'item_code' => $comment->item_code
        ])->with('success', 'Bình luận đã được cập nhật.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa.');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }


}