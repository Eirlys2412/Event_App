<?php

namespace App\Modules\Resource\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Resource\Models\ResourceType;
use Illuminate\Http\Request;


class ResourceTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $resourceTypes = ResourceType::orderBy('id', 'DESC')->paginate($this->pagesize);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách loại tài nguyên</li>';
        $active_menu = "resource_type_list";

        return view('Resource::type.index', compact('resourceTypes', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo loại tài nguyên</li>';
        $active_menu = "resource_type_add";

        return view('Resource::type.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        ResourceType::create($request->all());

        return redirect()->route('admin.resource-types.index')->with('success', 'Tạo loại tài nguyên thành công.');
    }

    public function edit($id)
    {
        $resourceType = ResourceType::findOrFail($id);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại tài nguyên</li>';
        $active_menu = "resource_type_edit";

        return view('Resource::type.edit', compact('resourceType', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'string|max:50',
        ]);

        $resourceType = ResourceType::findOrFail($id);
        $resourceType->update($request->all());

        return redirect()->route('admin.resource-types.index')->with('success', 'Cập nhật loại tài nguyên thành công.');
    }

    public function destroy($id)
    {
        $resourceType = ResourceType::findOrFail($id);
        $resourceType->delete();

        return redirect()->route('admin.resource-types.index')->with('success', 'Xóa loại tài nguyên thành công.');
    }

}
