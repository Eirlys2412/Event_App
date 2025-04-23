<?php

namespace App\Modules\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Blog\Models\Blog;
use App\Modules\Events\Models\TagEvent;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "blog_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $active_menu="blog_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách bài viết </li>';
        $blogs = Blog::orderBy('id','DESC')->paginate($this->pagesize);
        // categories
        return view('Blog::blog.index',compact('blogs','breadcrumb','active_menu'));

    }
    public function create()
    {
        $func = "blog_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $tags = Tag::where('status', 'active')->orderBy('title')->get();
        $data['categories'] = \App\Modules\Blog\Models\BlogCategory::where('status','active')->orderBy('title')->get();
        $data['tags'] = \App\Models\Tag::where('status','active')->orderBy('title')->get();
        $data['active_menu']="blog_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.blog.index').'">bài viết</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo bài viết </li>';
        return view('Blog::blog.create', $data, compact( 'tags'));
  
    }
     
    public function store(Request $request)
{
    $func = "blog_add";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Validate dữ liệu
    $validatedData = $request->validate([
        'title' => 'string|required',
        'photo' => 'string|nullable',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'new_tags' => 'nullable|string',
        'summary' => 'string|required',
        'content' => 'string|required',
        'cat_id' => 'numeric|nullable',
        'status' => 'required|in:active,inactive',
    ]);

    // Khởi tạo mảng tagsArray từ tags đã chọn
    $tagsArray = $request->tags ?? [];

    // Xử lý tag mới nhập vào (nếu có)
    if ($request->new_tags) {
        $newTags = explode(',', $request->new_tags);
        foreach ($newTags as $newTag) {
            $newTag = trim($newTag);
            if (!empty($newTag)) {
                $tag = Tag::firstOrCreate(['title' => $newTag]);
                $tagsArray[] = $tag->id;
            }
        }
    }

    // Xử lý slug
    $slug = Str::slug($validatedData['title']);
    $slugCount = Blog::where('slug', $slug)->count();
    if ($slugCount > 0) {
        $slug .= '-' . time();
    }

    // Xử lý content thông qua HelpController
    $helpController = new \App\Http\Controllers\HelpController();
    $content = $helpController->uploadImageInContent($validatedData['content']);
    $content = $helpController->removeImageStyle($content);

    // Xử lý ảnh mặc định nếu không có
    $photo = $validatedData['photo'] ?? asset('backend/images/profile-6.jpg');

    // Tạo Blog
    $blog = Blog::create([
        'title' => $validatedData['title'],
        'slug' => $slug,
        'summary' => $validatedData['summary'],
        'content' => $content,
        'photo' => $photo,
        'cat_id' => $validatedData['cat_id'] ?? null,
        'status' => $validatedData['status'],
        'tags' => json_encode($tagsArray),
        'user_id' => Auth::id(),
    ]);

    // Kiểm tra kết quả
    if ($blog) {
        return redirect()->route('admin.blog.index')->with('success', 'Tạo bài viết thành công!');
    } else {
        return back()->with('error', 'Có lỗi xảy ra!');
    }
}

 /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $func = "blog_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blog = Blog::findOrFail($id);
        $attachedTags = json_decode($blog->tags, true) ?? []; // Mặc định là mảng rỗng nếu không có dữ liệu
        $tags = Tag::where('status', 'active')->orderBy('title')->get();
        $categories = \App\Modules\Blog\Models\BlogCategory::where('status','active')->orderBy('title')->get();
        $blog = Blog::find($id);
        $tag_ids =DB::select("select tag_id from tag_blogs where blog_id = ".$blog->id)  ;
        if($blog)
        {
            $active_menu="blog_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.blog.index').'">bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh bài viết </li>';
            return view('Blog::blog.edit',compact('breadcrumb','blog','active_menu','categories','tags','attachedTags'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $func = "blog_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
    
        $blog = Blog::find($id);
        if (!$blog) {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    
        // Validate dữ liệu
        $validatedData = $request->validate([
            'title' => 'string|required',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string',
            'photo' => 'string|nullable',
            'summary' => 'string|required',
            'content' => 'string|required',
            'cat_id' => 'numeric|nullable',
            'status' => 'required|in:active,inactive',
        ]);
    
        // Xử lý tags
        $tagsArray = $validatedData['tags'] ?? [];
        if (!empty($request->new_tags)) {
            $newTags = explode(',', $request->new_tags);
            foreach ($newTags as $newTag) {
                $newTag = trim($newTag);
                if (!empty($newTag)) {
                    $tag = Tag::firstOrCreate(['title' => $newTag]);
                    $tagsArray[] = $tag->id;
                }
            }
        }
    
        // Xử lý nội dung qua HelpController
        $helpController = new \App\Http\Controllers\HelpController();
        $validatedData['content'] = $helpController->uploadImageInContent($validatedData['content']);
        $validatedData['content'] = $helpController->removeImageStyle($validatedData['content']);
    
        // Xử lý ảnh
        $validatedData['photo'] = $validatedData['photo'] ?? $blog->photo;
        if (empty($validatedData['photo'])) {
            $validatedData['photo'] = asset('backend/images/profile-6.jpg');
        }
    
        // Cập nhật slug
        $slug = Str::slug($validatedData['title']);
        $slugCount = Blog::where('slug', $slug)->where('id', '!=', $id)->count();
        if ($slugCount > 0) {
            $slug .= '-' . time();
        }
        $validatedData['slug'] = $slug;
    
        // Cập nhật blog
        $blog->fill($validatedData);
        $blog->tags = json_encode($tagsArray);
        $status = $blog->save();
    

    
        // Kết quả
        if ($status) {
            return redirect()->route('admin.blog.index')->with('success', 'Cập nhật thành công');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }
    
      /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $func = "blog_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $blog = Blog::find($id);
        if($blog)
        {
            $status = $blog->delete();
            if($status){
                return redirect()->route('admin.blog.index')->with('success','Xóa danh mục thành công!');
            }
            else
            {
                return back()->with('error','Có lỗi xãy ra!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function blogStatus(Request $request)
    {
        $func = "blog_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        if($request->mode =='true')
        {
            DB::table('blogs')->where('id',$request->id)->update(['status'=>'active']);
        }
        else
        {
            DB::table('blogs')->where('id',$request->id)->update(['status'=>'inactive']);
        }
        return response()->json(['msg'=>"Cập nhật thành công",'status'=>true]);
    }
    public function blogSearch(Request $request)
    {
        $func = "blog_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        if($request->datasearch)
        {
            $active_menu = "blog_list";
            $searchdata = $request->datasearch;
            $blogs = DB::table('blogs')
                ->where('title', 'LIKE', '%' . $searchdata . '%')
                ->orWhere('content', 'LIKE', '%' . $searchdata . '%')
                ->orderBy('id', 'DESC')
                ->paginate($this->pagesize)
                ->appends(request()->query());

            $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tìm kiếm bài viết: <strong>' . e($searchdata) . '</strong></li>';

            return view('Blog::blog.index', compact('blogs', 'breadcrumb', 'active_menu', 'searchdata'));
        } 
        else 
        {
            return redirect()->route('admin.blog.index')->with('error', 'Vui lòng nhập từ khóa tìm kiếm!');
        }
    }

}