<?php

namespace App\Modules\Resource\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceLinkType;
use App\Modules\Resource\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $resources = DB::table('resources')
        ->select ('resources.*','np.viewcode','np.code')

        ->leftJoin(\DB::raw("( select * from resource_link_types) as np "),'resources.link_code','=','np.code')
        ->paginate($this->pagesize);
        // dd( $resources);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách tài nguyên</li>';
        $active_menu = "resource_list";

        return view('Resource::index', compact('resources', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo tài nguyên</li>';
        $active_menu = "resource_add";

        return view('Resource::create', compact('resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu','tags'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'type_code' => 'required|exists:resource_types,code',
        'link_code' => 'nullable|exists:resource_link_types,code',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        
        'url' => 'nullable|url',
        'description' => 'nullable|string|max:25555',
        'tag_ids' => 'nullable|array',
    ]);

    $tag_ids = $request->tag_ids;
    
    // Tạo slug từ tiêu đề
    $slug = Str::slug($request->title);
    $count = Resource::where('slug', 'LIKE', "{$slug}%")->count();
    $timestamp = now()->format('YmdHis');
    if ($count > 0) {
        $slug .= '-' . ($count + 1);
    }
    $slug .= '-' . $timestamp;

    // Xử lý URL
    $url = $request->url ? $request->url : null;
    $youtubeID = $this->getYouTubeID($request->url);
    if ($youtubeID) {
        $url = "https://www.youtube.com/watch?v=" . $youtubeID;
    }

    // Xử lý tệp
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        
        if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY')) {
            $filePath = $file->storeAs('uploads/resources', $fileName, 's3');
            $url = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = $file->storeAs('uploads/resources', $fileName, 'public');
            $url = Storage::url($filePath);
            $url = Str::after($url, 'http://localhost');
        }
        
        $resource = Resource::create([
            'title' => $request->title,
            'slug' => $slug,
            'file_name' => $fileName,
            'description' => $request->description,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'path' => $filePath,
            'url' => $url,
            'type_code' => $request->type_code,
            'link_code' => $request->link_code,


        ]);
    } else {
        $resource = Resource::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'url' => $url,
            'type_code' => $request->type_code,
            'link_code' => $request->link_code,
            
        ]);

    }
    if ($tag_ids) {
        $tagservice = new \App\Http\Controllers\TagController();
        $tagservice->store_resource_tag($resource->id, $tag_ids);
    }

    return redirect()->route('admin.resources.index')->with('success', 'Tạo tài nguyên thành công.');
}

    public function edit($id)
    {

        $resource = Resource::findOrFail($id);
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();
        $tags  = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids =DB::select("select tag_id from tag_resources where resource_id = ".$resource->id);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa tài nguyên</li>';
        $active_menu = "resource_edit";

        return view('Resource::edit', compact('resource', 'resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu','tags','tag_ids'));
    }

    public function update(Request $request, $id)
{
    $resource = Resource::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'resource_type_id' => 'nullable|exists:resource_types,code',
        'resource_link_type_id' => 'nullable|exists:resource_link_types,code',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:2048000',

        'url' => 'nullable|url',
        'description' => 'nullable|string|max:25555'
    ]);
    $youtubeID = $this->getYouTubeID($request->url);
    if ($youtubeID) {
        
        $url = "https://www.youtube.com/watch?v=" . $youtubeID;
    } else {
        
        $url = $request->url;
    }

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Lưu tệp vào S3 hoặc local
        if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY')) {
            $filePath = $file->storeAs('uploads/resources', $fileName, 's3');
            $url = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = $file->storeAs('uploads/resources', $fileName, 'public');
            $url = Storage::url($filePath);
            $url = Str::after($url, 'http://localhost');
        }

        
        if (Storage::disk('public')->exists(str_replace('storage/', '', $resource->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $resource->path));
        }

        $resource->file_name = $fileName;
        $resource->file_type = $file->getClientMimeType();
        $resource->file_size = $file->getSize();
        $resource->path = 'storage/' . $filePath; 
        $resource->url = $url; 
    } else {
        $resource->url = $url;
    }

    $resource->title = $request->title;
    $resource->type_code = $request->type_code;
    $resource->link_code = $request->link_code;
    $resource->description = $request->description;



    $tagservice = new \App\Http\Controllers\TagController();
    $tag_ids = $request->tag_ids;
    $tagservice->update_resource_tag($resource->id, $tag_ids);

    $resource->save();

    return redirect()->route('admin.resources.index')->with('success', 'Cập nhật tài nguyên thành công.');
}


    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);

        if (Storage::disk('public')->exists(str_replace('storage/', '', $resource->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $resource->path));
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Xóa tài nguyên thành công.');
    }

    public function show($id)
    {
        $resource = Resource::where('id', $id)->firstOrFail();
        $tags  = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids =DB::select("select tag_id from tag_resources where resource_id = ".$resource->id);
        $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chi tiết tài nguyên</li>';
        $active_menu = "resource_detail";

        $resources = Resource::where('id', '!=', $id)->get();

        return view('Resource::show', compact('resource', 'resources', 'breadcrumb', 'active_menu','tags','tag_ids'));
    }
    private function getYouTubeID($url)
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+\?v=)|youtu\.be\/)([^&\n?#]+)/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    public function resourceSearch(Request $request)
    {
        $func = "resource_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        if ($request->datasearch) {
            $active_menu = "resource_list";
            $searchdata = $request->datasearch;
            $resources = DB::table('resources')->where('title', 'LIKE', '%' . $request->datasearch . '%')
                ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="' . route('admin.resources.index') . '">Tài nguyên</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Resource::search', compact('resources', 'breadcrumb', 'searchdata', 'active_menu'));
        } else {
            return redirect()->route('admin.resources.index')->with('success', 'Không có thông tin tìm kiếm!');
        }
    }
}
