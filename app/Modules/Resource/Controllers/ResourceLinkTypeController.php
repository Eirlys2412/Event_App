<?php

namespace App\Modules\Resource\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resource\Models\ResourceLinkType;
use Illuminate\Http\Request;

class ResourceLinkTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $resourceLinkTypes = ResourceLinkType::orderBy('id', 'DESC')->paginate($this->pagesize);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách loại liên kết tài nguyên</li>';
        $active_menu = "resource_link_type_list";

        return view('Resource::linktype.index', compact('resourceLinkTypes', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo loại liên kết tài nguyên</li>';
        $active_menu = "resource_link_type_add";

        return view('Resource::linktype.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'viewcode' => 'string||nullable',
        ]);

        ResourceLinkType::create($request->all());

        return redirect()->route('admin.resource-link-types.index')->with('success', 'Tạo loại liên kết tài nguyên thành công.');
    }

    public function edit($id)
    {
        $resourceLinkType = ResourceLinkType::findOrFail($id);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại liên kết tài nguyên</li>';
        $active_menu = "resource_link_type_edit";

        return view('Resource::linktype.edit', compact('resourceLinkType', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'string|max:50',
        ]);

        $resourceLinkType = ResourceLinkType::findOrFail($id);
        $resourceLinkType->update($request->all());

        return redirect()->route('admin.resource-link-types.index')->with('success', 'Cập nhật loại liên kết tài nguyên thành công.');
    }

    public function destroy($id)
    {
        $resourceLinkType = ResourceLinkType::findOrFail($id);
        $resourceLinkType->delete();

        return redirect()->route('admin.resource-link-types.index')->with('success', 'Xóa loại liên kết tài nguyên thành công.');
    }
}
