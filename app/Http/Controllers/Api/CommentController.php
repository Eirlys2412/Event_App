<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Yêu cầu xác thực qua API
    }

    // Lấy danh sách bình luận theo item_id và item_code
    public function index(Request $request)
{
    $itemType = $request->query('item_type'); // chuẩn param
    $itemId = $request->query('item_id');

    $comments = Comment::with('user')
                       ->where('item_code', $itemType) // chuẩn column DB
                       ->where('item_id', $itemId)
                       ->orderBy('created_at', 'asc')
                       ->get();

    return response()->json($comments, 200);
}


    // Tạo bình luận mới
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'item_code' => 'required|string|in:blog,event',
            'content' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'comment_resources' => 'nullable|file|mimes:jpeg,png,gif,jpg|max:5120', // 5MB
        ]);
    
        $commentData = [
            'item_id' => $request->item_id,
            'item_code' => $request->item_code,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ];
    
        if ($request->hasFile('comment_resources')) {
            $file = $request->file('comment_resources');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('comments', $filename, 'public');
            $commentData['comment_resources'] = $path;
        }
    
        $comment = Comment::create($commentData);
    
        return response()->json([
            'comment' => $comment->load('user'),
            'image_url' => $comment->comment_resources 
                ? url('storage/' . $comment->comment_resources) 
                : null
        ], 201);
    }

    // Cập nhật bình luận
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update(['content' => $request->content]);
        return response()->json($comment);
    }

    // Xóa bình luận
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
    public function reply(Request $request, $id)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    $parentComment = Comment::findOrFail($id);

    $reply = Comment::create([
        'item_id' => $parentComment->item_id,
        'item_code' => $parentComment->item_code,
        'user_id' => Auth::id(),
        'content' => $request->content,
        'parent_id' => $id,
    ]);

    return response()->json([
        'reply' => $reply->load('user'),
    ], 201);
}


}