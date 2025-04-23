<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\User;
use App\Modules\Tuongtac\Models\TPage;
use App\Modules\Tuongtac\Models\TPageItem;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\Validator;
use App\Modules\Tuongtac\Models\TComment;
use App\Modules\Tuongtac\Models\TNotice;
use App\Modules\Tuongtac\Models\TTag;
use App\Modules\Tuongtac\Models\TTagItem;
use App\Modules\Tuongtac\Models\TMotion;
use App\Modules\Tuongtac\Models\TMotionItem;
use App\Modules\Tuongtac\Models\TRecommend;
use App\Modules\Tuongtac\Models\TVoteItem;

class BlogController extends Controller
{
    // Lấy thông tin bài viết theo ID hoặc Slug
    public function getblog(Request $request)
    {
        $this->validate($request, [
            'slug' => 'string|nullable',
            'id' => 'integer|nullable',
        ]);

        $blog = null;
        if ($request->id) {
            $blog = Blog::find($request->id);
        }

        if ($request->slug) {
            $blog = Blog::where('slug', $request->slug)->first();
        }

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại.',
            ], 404);
        }

        // Lấy thông tin tác giả
        $author = User::find($blog->user_id);
        $blog->author_name = $author->full_name ?? null;
        $blog->author_photo = $author->photo ?? null;
        $blog->author_id = $author->id ?? null;

        // Dữ liệu tương tác (giả lập)
        $tuongtac = [
            'isBookmarked' => false,
            'countBookmarked' => 0,
            'reactions' => [],
            'hasComment' => 0,
            'comments' => [],
            'voteRecord' => null,
        ];

        return response()->json([
            'success' => true,
            'blog' => $blog,
            'tuongtac' => $tuongtac,
        ], 200);
    }

    // Lấy danh sách bài viết theo danh mục hoặc tag
    public function getBlogCat(Request $request)
    {
        $this->validate($request, [
            'catId' => 'integer|nullable',
            'tag' => 'string|nullable',
            'page' => 'integer|required',
            'limit' => 'integer|required',
        ]);

        $catId = $request->input('catId');
        $tag = $request->input('tag');
        $page = $request->input('page');
        $limit = $request->input('limit');
        $offset = ($page - 1) * $limit;

        $posts = collect();
        $total = 0;

        if ($tag) {
            $tagModel = Tag::where('title', $tag)->first();
            if ($tagModel) {
                $posts = DB::table('blogs')
                    ->whereJsonContains('tags', (string)$tagModel->id)
                    ->select(
                        'blogs.id',
                        'blogs.title',
                        'blogs.photo',
                        'blogs.summary',
                        'blogs.slug',
                        'blogs.created_at',
                        'blogs.tags'
                    )
                    ->orderBy('blogs.id', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                $total = DB::table('blogs')
                    ->whereJsonContains('tags', (string)$tagModel->id)
                    ->count();

                $posts = $posts->map(function ($post) {
                    $tagIds = json_decode($post->tags, true);
                    $post->tags = Tag::whereIn('id', $tagIds)->pluck('title')->toArray();
                    return $post;
                });
            }
        }

        if ($catId) {
            $posts = DB::table('blogs')
                ->select(
                    'id',
                    'title',
                    'photo',
                    'summary',
                    'slug',
                    'created_at',
                    'tags'
                )
                ->where('cat_id', $catId)
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $total = DB::table('blogs')
                ->where('cat_id', $catId)
                ->count();

            $posts = $posts->map(function ($post) {
                $tagIds = json_decode($post->tags, true);
                $post->tags = Tag::whereIn('id', $tagIds)->pluck('title')->toArray();
                return $post;
            });
        }

        return response()->json([
            'data' => $posts,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
        ]);
    }

    // Tìm kiếm bài viết theo từ khóa
    public function getBlogSearch(Request $request)
    {
        $this->validate($request, [
            'search' => 'string|nullable',
            'page' => 'integer|required',
            'limit' => 'integer|required',
        ]);

        $searchdata = $request->search;
        $sdatas = explode(" ", $searchdata);
        $searchdata = implode("%", $sdatas);
        $page = $request->input('page');
        $limit = $request->input('limit');
        $offset = ($page - 1) * $limit;

        $posts = DB::table('blogs')
            ->select('id', 'title', 'photo', 'summary', 'slug', 'created_at')
            ->where('title', 'like', '%' . $searchdata . '%')
            ->orWhere('summary', 'like', '%' . $searchdata . '%')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $tempposts = DB::table('blogs')
            ->select('id', 'title', 'photo', 'summary', 'slug', 'created_at')
            ->where('title', 'like', '%' . $searchdata . '%')
            ->orWhere('summary', 'like', '%' . $searchdata . '%')
            ->orderBy('id', 'desc')
            ->get();

        $total = count($tempposts);

        return response()->json([
            'data' => $posts,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => ceil($total / $limit),
        ]);
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        set_time_limit(6000);

        $this->validate($request, [
            'title' => 'string|required',
            'photo' => 'string|nullable',
            'summary' => 'string|nullable',
            'content' => 'string|required',
            'tag_ids' => 'string|nullable',
            'blogtype' => 'string|nullable',
        ]);

        $slug = Str::slug($request->input('title'));
        $slug_count = Blog::where('slug', $slug)->count();
        if ($slug_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết đã có.',
            ], 200);
        }

        $data = $request->all();
        $data['slug'] = $slug;

        $helpController = new \App\Http\Controllers\HelpController();
        $fileController = new \App\Http\Controllers\FilesController();

        $data['content'] = $helpController->removeImageStyle($data['content']);
        $data['photo'] = $fileController->blogimageUpload($data['photo'] ?? 'default.jpg');
        $data['user_id'] = auth()->user()->id;

        $blog = Blog::create($data);

        if ($blog) {
            // Xử lý gắn tag cho bài viết
            $tagcontroller = new \App\Http\Controllers\TagController();
            $tag_ids = json_decode($data['tag_ids']);
            $tagcontroller->store_blog_tag($blog->id, $tag_ids);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu thành công!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại!',
            ], 200);
        }
    }
}
