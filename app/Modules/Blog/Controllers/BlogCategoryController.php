<?php

namespace App\Modules\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Blog\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
    }

    public function index()
    {
        
        $blogcats = BlogCategory::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Blog::blogcat.index', [
            'blogcats' => $blogcats,
            'breadcrumb' => '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item active" aria-current="page">Danh mục bài viết</li>',
            'active_menu' => 'blogcat_list'
        ]);
    }

    public function blogcatSearch(Request $request)
    {
        $searchdata = $request->datasearch;

        if (!$searchdata) {
            return redirect()->route('admin.blogcategory.index')->with('error', 'Vui lòng nhập từ khóa tìm kiếm!');
        }

        $blogcats = BlogCategory::where('title', 'LIKE', "%$searchdata%")
            ->paginate($this->pagesize)
            ->appends(request()->query());

        return view('Blog::blogcat.search', [
            'blogcats' => $blogcats,
            'breadcrumb' => '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item"><a href="' . route('admin.blogcategory.index') . '">Danh mục bài viết</a></li><li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>',
            'active_menu' => 'blogcat_list',
            'searchdata' => $searchdata
        ]);
    }

    public function create()
    {
        return view('Blog::blogcat.create', [
            'breadcrumb' => '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item"><a href="' . route('admin.blogcategory.index') . '">Danh mục bài viết</a></li><li class="breadcrumb-item active" aria-current="page">Tạo danh mục bài viết</li>',
            'active_menu' => 'blogcat_add'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'photo' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($validated['title']);
        if (BlogCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $validated['slug'] = $slug;
        $validated['photo'] = $validated['photo'] ;
        $photoLinks = explode(',', $request->input('photo')); // tách chuỗi link
$data['photo'] = json_encode($photoLinks);


        BlogCategory::create($validated);

        return redirect()->route('admin.blogcategory.index')->with('success', 'Tạo danh mục bài viết thành công!');
    }

    public function edit($id)
    {
        $blogcat = BlogCategory::findOrFail($id);
        return view('Blog::blogcat.edit', [
            'blogcat' => $blogcat,
            'breadcrumb' => '<li class="breadcrumb-item"><a href="#">/</a></li><li class="breadcrumb-item"><a href="' . route('admin.blogcategory.index') . '">Danh mục bài viết</a></li><li class="breadcrumb-item active" aria-current="page">Điều chỉnh mục bài viết</li>',
            'active_menu' => 'blogcat_list'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'photo' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $blogcat = BlogCategory::findOrFail($id);
        $photoLinks = explode(',', $request->input('photo')); // tách chuỗi link
$data['photo'] = json_encode($photoLinks);


        if (empty($validated['photo'])) {
            $validated['photo'] = $blogcat->photo;
        }

        $blogcat->update($validated);

        return redirect()->route('admin.blogcategory.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $blogcat = BlogCategory::findOrFail($id);
        $blogcat->delete();
        return redirect()->route('admin.blogcategory.index')->with('success', 'Xóa danh mục thành công!');
    }

    public function blogcatStatus(Request $request)
    {
        $blogcat = BlogCategory::findOrFail($request->id);
        $blogcat->status = $request->mode === 'true' ? 'active' : 'inactive';
        $blogcat->save();

        return response()->json(['msg' => 'Cập nhật trạng thái thành công', 'status' => true]);
    }
}
