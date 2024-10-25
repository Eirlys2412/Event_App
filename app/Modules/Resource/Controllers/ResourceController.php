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
        $resources = Resource::orderBy('id', 'DESC')->paginate($this->pagesize);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách tài nguyên</li>';
        $active_menu = "resource_list";

        return view('Resource::index', compact('resources', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo tài nguyên</li>';
        $active_menu = "resource_add";

        return view('Resource::create', compact('resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resource_type_id' => 'nullable|exists:resource_types,id',
            'resource_link_type_id' => 'nullable|exists:resource_link_types,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
            'tags' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'document_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string|max:25555'
        ]);

        $slug = Str::slug($request->title);
        $count = Resource::where('slug', 'LIKE', "{$slug}%")->count();
        $timestamp = now()->format('YmdHis');
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $slug .= '-' . $timestamp;

        $youtubeID = $this->getYouTubeID($request->youtube_url);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Kiểm tra xem có S3 hay không, nếu có thì lưu vào S3, nếu không thì lưu vào local
            if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY')) {
                $filePath = $file->storeAs('uploads/resources', $fileName, 's3');
                $url = Storage::disk('s3')->url($filePath);
            } else {
                $filePath = $file->storeAs('uploads/resources', $fileName, 'public');
                $url = Storage::url($filePath);
                $url = Str::after($url, 'http://localhost');
            }

            Resource::create([
                'title' => $request->title,
                'slug' => $slug,
                'file_name' => $fileName? $fileName : null,
                'description' => $request->description,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'path' => $filePath ? $filePath : null,
                'url' => $url ? $url : null,
                'resource_type_id' => $request->resource_type_id,
                'resource_link_type_id' => $request->resource_link_type_id,
                'tags' => $request->tags,
                'youtube_url' => $youtubeID ? "https://www.youtube.com/watch?v=$youtubeID" : null,
                'document_url' => $request->document_url,
                'image_url' => $request->image_url,
            ]);
        } else {
            // Nếu không có tệp, kiểm tra các loại URL
            if ($youtubeID || $request->document_url || $request->image_url) {
                Resource::create([
                    'title' => $request->title,
                    'slug' => $slug,
                    'description' => $request->description,
                    'youtube_url' => $youtubeID ? "https://www.youtube.com/watch?v=$youtubeID" : null,
                    'document_url' => $request->document_url,
                    'image_url' => $request->image_url,
                    'resource_type_id' => $request->resource_type_id,
                    'resource_link_type_id' => $request->resource_link_type_id,
                    'tags' => $request->tags,
                ]);
            } else {
                return redirect()->back()->withErrors(['file' => 'Bạn phải chọn tệp hoặc nhập ít nhất một trong các liên kết YouTube, Document hoặc Image.']);
            }
        }
        return redirect()->route('admin.resources.index')->with('success', 'Tạo tài nguyên thành công.');
    }

    public function edit($id)
    {

        $resource = Resource::findOrFail($id);
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa tài nguyên</li>';
        $active_menu = "resource_edit";

        return view('Resource::edit', compact('resource', 'resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'resource_type_id' => 'nullable|exists:resource_types,id',
            'resource_link_type_id' => 'nullable|exists:resource_link_types,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:2048000',
            'tags' => 'nullable|string',
            'youtube_url' => 'nullable|url',
            'document_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string|max:25555'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Kiểm tra xem có S3 hay không, nếu có thì lưu vào S3, nếu không thì lưu vào local
            if (env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY')) {
                $filePath = $file->storeAs('uploads/resources', $fileName, 's3');
                $url = Storage::disk('s3')->url($filePath);
            } else {
                $filePath = $file->storeAs('uploads/resources', $fileName, 'public');
                $url = Storage::url($filePath);
                $url = Str::after($url, 'http://localhost');
            }

            // Xóa tệp cũ
            if (Storage::disk('public')->exists(str_replace('storage/', '', $resource->path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $resource->path));
            }

            $resource->file_name = $fileName;
            $resource->file_type = $file->getClientMimeType();
            $resource->file_size = $file->getSize();
            $resource->path = 'storage/' . $filePath; 
            $resource->url = $url; 
        }

        $resource->title = $request->title;
        $resource->resource_type_id = $request->resource_type_id;
        $resource->resource_link_type_id = $request->resource_link_type_id;
        $resource->description = $request->description;
        $resource->tags = $request->tags;

        $youtubeID = $this->getYouTubeID($request->youtube_url);
        $resource->youtube_url = $youtubeID ? "https://www.youtube.com/watch?v=$youtubeID" : null;
        $resource->document_url = $request->document_url;
        $resource->image_url = $request->image_url;
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
        $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chi tiết tài nguyên</li>';
        $active_menu = "resource_detail";

        $resources = Resource::where('id', '!=', $id)->get();

        return view('Resource::show', compact('resource', 'resources', 'breadcrumb', 'active_menu'));
    }
    private function getYouTubeID($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return isset($query['v']) ? $query['v'] : null;
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
