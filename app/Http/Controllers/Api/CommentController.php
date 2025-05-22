<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Comments\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\TuongTac\Models\Like;

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

        $comments = Comment::with(['user', 'likes'])
                           ->where('item_code', $itemType) // chuẩn column DB
                           ->where('item_id', $itemId)
                           ->orderBy('created_at', 'asc')
                           ->get();

        // Append accessors to each comment
        $comments->each(function ($comment) {
            $comment->append(['likes_count', 'is_liked']);
        });

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
        return response()->json($comment->append(['likes_count', 'is_liked']));
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

        // Load user relationship and append accessors for the reply
        $reply->load('user');
        $reply->append(['likes_count', 'is_liked']);

        return response()->json([
            'reply' => $reply,
        ], 201);
    }

    // API để thích/bỏ thích bình luận
    public function toggleLike($id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Nếu người dùng đã thích, hãy bỏ thích
            $like->delete();
            $isLiked = false;
        } else {
            // Nếu người dùng chưa thích, hãy thích
            $comment->likes()->create([
                'user_id' => $user->id,
                'likeable_id' => $comment->id,
                'likeable_type' => get_class($comment),
            ]);
            $isLiked = true;
        }

        // Trả về số lượt thích mới và trạng thái thích của người dùng
        $comment->refresh(); // Làm mới model để lấy số lượt thích mới nhất
        $comment->append(['likes_count', 'is_liked']);

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Bình luận đã được thích.' : 'Bình luận đã được bỏ thích.',
            'likes_count' => $comment->likes_count,
            'is_liked' => $comment->is_liked,
        ]);
    }
}