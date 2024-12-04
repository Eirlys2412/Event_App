<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Exercise\Models\TuluancauhoiLinkType; // Đảm bảo đã tạo model này
use Illuminate\Http\Request;

class TuluancauhoiLinkTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20); // Số bản ghi trên mỗi trang
        $this->middleware('auth'); // Bảo vệ các phương thức bằng middleware xác thực
    }

    public function index()
    {
        $linkTypes = TuluancauhoiLinkType::orderBy('id', 'DESC')->paginate($this->pagesize);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách loại liên kết câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_link_type_list";

        return view('Exercise::tuluancauhoi_link_type.index', compact('linkTypes', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo loại liên kết câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_link_type_add";

        return view('Exercise::tuluancauhoi_link_type.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:tuluancauhoi_link_types',
            'viewcode' => 'nullable|string|max:50',
        ]);

        TuluancauhoiLinkType::create($request->all());

        return redirect()->route('admin.tuluancauhoi-link-types.index')->with('success', 'Tạo loại liên kết câu hỏi tự luận thành công.');
    }

    public function edit($id)
    {
        $linkType = TuluancauhoiLinkType::findOrFail($id);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại liên kết câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_link_type_edit";

        return view('Exercise::tuluancauhoi_link_type.edit', compact('linkType', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $linkType = TuluancauhoiLinkType::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:tuluancauhoi_link_types,code,' . $linkType->id,
            'viewcode' => 'nullable|string|max:50',
        ]);

        $linkType->update($request->all());

        return redirect()->route('admin.tuluancauhoi-link-types.index')->with('success', 'Cập nhật loại liên kết câu hỏi tự luận thành công.');
    }

    public function destroy($id)
    {
        $linkType = TuluancauhoiLinkType::findOrFail($id);
        $linkType->delete();

        return redirect()->route('admin.tuluancauhoi-link-types.index')->with('success', 'Xóa loại liên kết câu hỏi tự luận thành công.');
    }
}