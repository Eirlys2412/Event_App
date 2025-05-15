<?php

namespace App\Modules\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Blog\Models\Blog;
use App\Modules\Blog\Models\BlogCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
    }

    public function index()
    {
        $active_menu = 'blog_list';
        $blogs = Blog::orderBy('id','DESC')->paginate($this->pagesize);
        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item active" aria-current="page">Danh sách bài viết</li>';
        return view('Blog::blog.index', compact('blogs', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $active_menu = 'blog_list';
        $categories = BlogCategory::where('status','active')->orderBy('title')->get();
        $tags = Tag::where('status','active')->orderBy('title')->get();
        return view('Blog::blog.create', compact('categories', 'tags', 'active_menu'));
    }

    public function store(Request $request)
    {
        $active_menu = 'blog_list';
        $validated = $request->validate([
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:pending,approved',
            'cat_id' => 'nullable|integer',
            'photo' => 'nullable|string',
            'tags' => 'nullable|array',
            'new_tags' => 'nullable|string'
        ]);

        // Xử lý tag
        $tags = $validated['tags'] ?? [];
        if (!empty($validated['new_tags'])) {
            $newTags = array_map('trim', explode(',', $validated['new_tags']));
            foreach ($newTags as $newTag) {
                if ($newTag !== '') {
                    $tag = Tag::firstOrCreate(['title' => $newTag]);
                    $tags[] = $tag->id;
                }
            }
        }

        $file = $request->input('photo');
        $validated['photo'] = !empty($file) ? $file : '';

        // Xử lý slug
        $slug = Str::slug($validated['title']);
        if (Blog::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

         $help = new \App\Http\Controllers\HelpController();
        $content = $help->uploadImageInContent($validated['content']);
        $content = $help->removeImageStyle($content);

        // Tạo blog
        $blog = Blog::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content' => $content,
            'status' => $validated['status'],
            'cat_id' => $validated['cat_id'],
            'photo' => $validated['photo'],
            'tags' => json_encode($tags),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Tạo bài viết thành công!');
    }

    public function edit($id)
    {
        $active_menu = 'blog_list';
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::where('status','active')->orderBy('title')->get();
        $tags = Tag::where('status','active')->orderBy('title')->get();
        $attachedTags = json_decode($blog->tags, true) ?? [];
        return view('Blog::blog.edit', compact('blog', 'categories', 'tags', 'attachedTags', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $active_menu = 'blog_list';
        $validated = $request->validate([
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:pending,approved',
            'cat_id' => 'nullable|integer',
            'photo' => 'nullable|string',
            'tags' => 'nullable|array',
            'new_tags' => 'nullable|string'
        ]);
        
        $help = new \App\Http\Controllers\HelpController();
        $validated['content'] = $help->removeImageStyle($help->uploadImageInContent($validated['content']));

        $file = $request->input('photo');
        if (empty($file)) {
            $validated['photo'] = '';
        } else {
            $validated['photo'] = $file;
        }

        $tags = $validated['tags'] ?? [];
        if (!empty($validated['new_tags'])) {
            $newTags = array_map('trim', explode(',', $validated['new_tags']));
            foreach ($newTags as $newTag) {
                if ($newTag !== '') {
                    $tag = Tag::firstOrCreate(['title' => $newTag]);
                    $tags[] = $tag->id;
                }
            }
        }

       

        $validated['slug'] = Str::slug($validated['title']);
        if (Blog::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
            $validated['slug'] .= '-' . time();
        }

        $validated['tags'] = json_encode($tags);

        Blog::findOrFail($id)->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $active_menu = 'blog_list';
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Xóa bài viết thành công!');
    }

    public function blogStatus(Request $request)
    {
        $active_menu = 'blog_list';
        $blog = Blog::findOrFail($request->id);
        $blog->status = $request->mode === 'true' ? 'approved' : 'pending';
        $blog->save();
        return response()->json(['msg' => 'Cập nhật trạng thái thành công', 'status' => true]);
    }

    public function blogSearch(Request $request)
    {
        $search = $request->datasearch;
        $blogs = Blog::where('title', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->paginate($this->pagesize);

        return view('Blog::blog.index', [
            'blogs' => $blogs,
            'breadcrumb' => '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item active">Tìm kiếm bài viết</li>',
            'active_menu' => 'blog_list',
            'searchdata' => $search
        ]);
    }
}
