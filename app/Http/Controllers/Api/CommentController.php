<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Comments\Models\Comment;
use App\Models\User;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Yêu cầu xác thực qua API
    }

    // GET /api/v1/comments?commentable_type=&commentable_id=
    public function index(Request $request)
    {
        $type = $request->query('commentable_type');
        $id   = $request->query('commentable_id');
        $comments = Comment::with('user')
            ->where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($comments, 200);
    }

    // POST /api/v1/comments
    public function store(Request $request)
    {
        $data = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id'   => 'required|integer',
            'content'          => 'required|string',
            'parent_id'        => 'nullable|integer',
        ]);
        $data['user_id'] = Auth::id();
        $comment = Comment::create($data);

        // Notify owner if not self
        $modelClass = $data['commentable_type'];
        if (class_exists($modelClass)) {
            $model = $modelClass::find($data['commentable_id']);
            if ($model && isset($model->user_id) && $model->user_id !== Auth::id()) {
                User::find($model->user_id)
                    ->notify(new NewCommentNotification($comment));
            }
        }

        return response()->json($comment, 201);
    }

    // PUT /api/v1/comments/{id}
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = $request->validate(['content' => 'required|string']);
        $comment->update($data);
        return response()->json($comment, 200);
    }

    // DELETE /api/v1/comments/{id}
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $comment->delete();
        return response()->json(null, 204);
    }
}