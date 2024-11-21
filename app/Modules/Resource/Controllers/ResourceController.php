<?php

namespace App\Modules\Resource\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceLinkType;
use App\Modules\Resource\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '10');
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Resource::query();
        if ($type = $request->input('type')) {
            $query->where('type_code', $type);
        }
        if ($linkType = $request->input('linkType')) {
            $query->where('link_code', $linkType)->whereNotNull('link_code');
        }
        if ($uploader = $request->input('uploader')) {
            $query->where('code', $uploader);
        }

        $resources = $query->paginate($this->pagesize);
        $resourceTypes = Resource::select('type_code')->distinct()->get();
        $linkTypes = Resource::select('link_code')->distinct()->whereNotNull('link_code')->get();
        $uploaderSources = Resource::select('code')->distinct()->get();

        $viewMode = $request->input('view_mode', 'pagination');
        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách tài nguyên</li>';
        $active_menu = "resource_list";

        return view('Resource::index', compact('resources', 'breadcrumb', 'active_menu', 'viewMode', 'resourceTypes', 'linkTypes', 'uploaderSources'));
    }

    public function create()
    {
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.resources.index') . '">Danh sách tài nguyên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo tài nguyên</li>';
        $active_menu = "resource_add";

        return view('Resource::create', compact('resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu', 'tags'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'link_code' => 'nullable|exists:resource_link_types,code',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
            'code' => 'nullable|string',
            'url' => 'nullable|url',
            'description' => 'nullable|string|max:25555',
            'tag_ids' => 'nullable|array',
        ]);

        $resourceType = ResourceType::first();
        $linkTypes = ResourceLinkType::first();
        if (!$resourceType) {
            return redirect()->back()->with('error', 'Không tìm thấy loại tài nguyên.');
        }
        if (!$linkTypes) {
            return redirect()->back()->with('error', 'Không tìm thấy loại link.');
        }
        $data = $request->only(['title', 'url', 'description']);
        $data['type_code'] = $resourceType->code;
        $data['link_code'] = $linkTypes->code;

        $resource = Resource::createResource((object) $data, $request->file('file'), 'Resource');

        if ($request->tag_ids) {
            $tagService = new \App\Http\Controllers\TagController();
            $tagService->store_resource_tag($resource->id, $request->tag_ids);
        }

        return redirect()->route('admin.resources.index')->with('success', 'Tạo tài nguyên thành công.');
    }


    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $resourceTypes = ResourceType::all();
        $linkTypes = ResourceLinkType::all();
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tag_ids = DB::table('tag_resources')->where('resource_id', $resource->id)->pluck('tag_id')->toArray();

        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.resources.index') . '">Danh sách tài nguyên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa tài nguyên</li>';
        $active_menu = "resource_edit";

        return view('Resource::edit', compact('resource', 'resourceTypes', 'linkTypes', 'breadcrumb', 'active_menu', 'tags', 'tag_ids'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type_code' => 'required|exists:resource_types,code',
            'link_code' => 'nullable|exists:resource_link_types,code',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
            'code' => 'nullable|string',
            'url' => 'nullable|url',
            'description' => 'nullable|string|max:25555',
            'tag_ids' => 'nullable|array',
        ]);

        $resourceType = ResourceType::where('code', $request->type_code)->first();
        if (!$resourceType) {
            return redirect()->back()->with('error', 'Không tìm thấy loại tài nguyên với mã ' . $request->type_code);
        }

        if ($request->link_code) {
            $linkTypes = ResourceLinkType::where('code', $request->link_code)->first();
            if (!$linkTypes) {
                return redirect()->back()->with('error', 'Không tìm thấy loại link với mã ' . $request->link_code);
            }
        }

        $data = $request->only(['title', 'type_code', 'link_code', 'url', 'description']);
        $data['type_code'] = $resourceType->code;
        if ($request->link_code) {
            $data['link_code'] = $linkTypes->code;
        }

        $resource->updateResource($data, $request->file('file'));

        if ($request->tag_ids) {
            $tagService = new \App\Http\Controllers\TagController();
            $tagService->store_resource_tag($resource->id, $request->tag_ids);
        }

        return redirect()->route('admin.resources.index')->with('success', 'Cập nhật tài nguyên thành công.');
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->deleteResource();

        return redirect()->route('admin.resources.index')->with('success', 'Xóa tài nguyên thành công.');
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tag_ids = DB::table('tag_resources')->where('resource_id', $resource->id)->pluck('tag_id')->toArray();

        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.resources.index') . '">Danh sách tài nguyên</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết tài nguyên</li>';
        $active_menu = "resource_detail";

        $resources = Resource::where('id', '!=', $id)->get();

        return view('Resource::show', compact('resource', 'resources', 'breadcrumb', 'active_menu', 'tags', 'tag_ids'));
    }

    public function resourceSearch(Request $request)
    {
        $searchdata = $request->input('datasearch');
        $viewMode = $request->input('view_mode', 'pagination');
        $typeFilter = $request->input('type');
        $linkTypeFilter = $request->input('linkType');
        $uploaderFilter = $request->input('uploader');

        $query = Resource::query();

        if ($typeFilter) {
            $query->where('type_code', $typeFilter);
        }
        if ($linkTypeFilter) {
            $query->where('link_code', $linkTypeFilter);
        }
        if ($uploaderFilter) {
            $query->where('code', $uploaderFilter);
        }

        $resources = $query->paginate($this->pagesize);
        $resourceTypes = Resource::select('type_code')->distinct()->get();
        $linkTypes = Resource::select('link_code')->distinct()->get();
        $uploaderSources = Resource::select('code')->distinct()->get();
        if ($searchdata) {
            $active_menu = "resource_list";

            // Retrieve resources based on search term
            $resources = Resource::where('title', 'LIKE', '%' . $searchdata . '%')
                ->paginate($this->pagesize)
                ->withQueryString();

            $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.resources.index') . '">Danh sách tài nguyên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>';
            if ($request->ajax()) {
                return view('Resource::ajax', compact('resources', 'viewMode'));
            }

            return view('Resource::search', compact('uploaderSources', 'resources', 'breadcrumb', 'searchdata', 'active_menu', 'viewMode', 'resourceTypes', 'linkTypes'));
        }

        return redirect()->route('admin.resources.index')->with('error', 'Không có thông tin tìm kiếm!');
    }
}
