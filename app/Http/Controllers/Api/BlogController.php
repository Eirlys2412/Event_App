<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modules\Blog\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\TagController;
use App\Modules\TuongTac\Models\Bookmark;
use App\Modules\TuongTac\Models\Like;
use App\Modules\TuongTac\Models\Vote;
class BlogController extends Controller
{
    public function getAll()
    {
        $blogs = Blog::orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    public function getBlog(Request $request)
    {
        $request->validate([
            'slug' => 'nullable|string',
            'id'   => 'nullable|integer',

        ]);

        $blog = $request->id 
            ? Blog::find($request->id) 
            : Blog::where('slug', $request->slug)->first();

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Bài viết không tồn tại.'], 404);
        }

        $author = User::find($blog->user_id);
        $blog->author_name = $author->full_name ?? null;
        $blog->author_photo = $author->photo ?? null;
        $blog->author_id = $author->id ?? null;

        return response()->json([
            'success' => true,
            'blog' => $blog,
            'tuongtac' => [
                'isBookmarked' => false,
                'countBookmarked' => 0,
                'reactions' => [],
                'hasComment' => 0,
                'comments' => [],
                'voteRecord' => null,
            ],
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'catId' => 'nullable|integer',
            'tag' => 'nullable|string',
            'page' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        $query = Blog::query();

        if ($request->catId) {
            $query->where('cat_id', $request->catId);
        }

        if ($request->tag) {
            $tag = Tag::where('title', $request->tag)->first();
            if ($tag) {
                $query->whereJsonContains('tags', (string)$tag->id);
            }
        }

        $total = $query->count();
        $blogs = $query->orderByDesc('id')
            ->offset(($request->page - 1) * $request->limit)
            ->limit($request->limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $blogs,
            'current_page' => $request->page,
            'per_page' => $request->limit,
            'total' => $total,
            'total_pages' => ceil($total / $request->limit),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'page' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        $keyword = str_replace(' ', '%', $request->search);

        $query = Blog::query()
            ->where('title', 'like', "%$keyword%")
            ->orWhere('summary', 'like', "%$keyword%");

        $total = $query->count();
        $results = $query->orderByDesc('id')
            ->offset(($request->page - 1) * $request->limit)
            ->limit($request->limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
            'current_page' => $request->page,
            'per_page' => $request->limit,
            'total' => $total,
            'total_pages' => ceil($total / $request->limit),
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'summary' => 'nullable|string',
        'content' => 'required|string',
        'image' => 'nullable|string', // base64 image từ Flutter
        'tag_ids' => 'nullable|string',
        'category_id' => 'nullable|integer',
        'status' => 'pending',
    ]);

    DB::beginTransaction();
    try {
        $slug = Str::slug($request->title);
        if (Blog::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $help = new HelpController();
        $photoPath = null;

        // ✅ Xử lý ảnh base64 nếu có
        if ($request->has('image') && !empty($request->image)) {
            $base64Image = $request->image;
            $imageName = 'blog_' . time() . '_' . Str::random(10) . '.jpg';
            $imagePath = 'avatar/' . $imageName;

            // Ghi file vào storage/app/public/avatar/
            Storage::disk('public')->put($imagePath, base64_decode($base64Image));

            $photoPath = asset('storage/' . $imagePath); // Đường dẫn để lưu vào DB
        }

        $blog = Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'summary' => $request->summary,
            'content' => $help->removeImageStyle($request->content),
            'photo' => $photoPath,
            'user_id' => Auth::id(),
            'cat_id' => $request->category_id,
            'status' => $request->status ?? 'active',
        ]);

        // Gán tag nếu có
        if ($request->tag_ids) {
            $tagIds = json_decode($request->tag_ids, true);
            (new TagController())->store_blog_tag($blog->id, $tagIds);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Bài viết đã được lưu.',
            'data' => $blog,
        ], 201);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string',
        'summary' => 'nullable|string',
        'content' => 'required|string',
        'image' => 'nullable|string', // đổi từ 'photo' thành 'image' cho đồng bộ
        'tag_ids' => 'nullable|string',
        'category_id' => 'nullable|integer',
        'status' => 'pending',
    ]);

    $blog = Blog::findOrFail($id);

    $help = new HelpController();
    $photoPath = $blog->photo;

    // ✅ Nếu cập nhật ảnh mới (base64)
    if ($request->has('image') && !empty($request->image)) {
        $base64Image = $request->image;
        $imageName = 'blog_' . time() . '_' . Str::random(10) . '.jpg';
        $imagePath = 'avatar/' . $imageName;

        Storage::disk('public')->put($imagePath, base64_decode($base64Image));

        $photoPath = asset('storage/' . $imagePath);
    }

    $blog->update([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'summary' => $request->summary,
        'content' => $help->removeImageStyle($request->content),
        'photo' => $photoPath,
        'cat_id' => $request->category_id ?? $blog->cat_id,
        'status' => $request->status ?? $blog->status,
    ]);

    if ($request->tag_ids) {
        $tagIds = json_decode($request->tag_ids, true);
        (new TagController())->store_blog_tag($blog->id, $tagIds);
    }

    return response()->json([
        'success' => true,
        'message' => 'Cập nhật thành công.',
        'data' => $blog,
    ]);
}


    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Bài viết đã được xóa.']);
    }

    public function attachTags(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        
        $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $blog->tags()->sync($request->tags);

        return response()->json([
            'success' => true,
            'message' => 'Tags attached successfully',
            'data' => $blog->load('tags')
        ]);
    }

    public function getTags($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $blog->tags
        ]);
    }
    public function getmyBlogs()
    {
        try {
            $userId = Auth::id();
            $blogs = Blog::where('user_id', $userId)
                ->where('status', 'approved')
                ->orderByDesc('id')
                ->get();
            return response()->json([
                'success' => true,
                'data' => $blogs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
public function getBlogsByUser($id)
{
    $blogs = Blog::where('user_id', $id)
                 ->where('status', 'approved')
                 ->orderByDesc('id')
                 ->get();

    return response()->json([
        'success' => true,
        'data' => $blogs
    ]);
}
public function getApprovedBlogs()
{
    $blogs = Blog::where('status', 'approved')
        ->orderBy('created_at', 'desc')
        ->select('id', 'title', 'slug', 'summary', 'content', 'cat_id', 'photo', 'created_at', 'updated_at', 'user_id')
        ->paginate(10);

    // Bổ sung thông tin tác giả và tương tác cho từng bài viết
    $blogs->getCollection()->transform(function ($blog) {
        $author = User::find($blog->user_id);
        $blog->author_name = $author->full_name ?? null;
        $blog->author_photo = $author->photo ?? null;
        $blog->author_id = $author->id ?? null;

        // Số lượng bookmark, like, comment, vote
        $blog->countBookmarked = $blog->bookmarks()->count();
        $blog->countLike = $blog->likes()->count();
        $blog->countComment = $blog->comments()->count();
        //$blog->vote = $blog->votes()->avg('rating'); // hoặc count nếu muốn

        return $blog;
    });

    return response()->json([
        'success' => true,
        'blogs' => $blogs,
    ]);
}



}