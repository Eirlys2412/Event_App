<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Exercise\Models\TuluancauhoiType;
use Illuminate\Http\Request;

class TuluancauhoiTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $tuluancauhoiTypes = TuluancauhoiType::orderBy('id', 'DESC')->paginate($this->pagesize);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách loại câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_type_list";

        return view('Exercise::tuluancauhoi_type.index', compact('tuluancauhoiTypes', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo loại câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_type_add";

        return view('Exercise::tuluancauhoi_type.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:tuluancauhoi_types,code',
        ]);

        TuluancauhoiType::create($request->all());

        return redirect()->route('admin.tuluancauhoi-types.index')->with('success', 'Tạo loại câu hỏi tự luận thành công.');
    }

    public function edit($id)
    {
        $tuluancauhoiType = TuluancauhoiType::findOrFail($id);
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_type_edit";

        return view('Exercise::tuluancauhoi_type.edit', compact('tuluancauhoiType', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:tuluancauhoi_types,code,' . $id,
        ]);

        $tuluancauhoiType = TuluancauhoiType::findOrFail($id);
        $tuluancauhoiType->update($request->all());

        return redirect()->route('admin.tuluancauhoi-types.index')->with('success', 'Cập nhật loại câu hỏi tự luận thành công.');
    }

    public function destroy($id)
    {
        $tuluancauhoiType = TuluancauhoiType::findOrFail($id);
        $tuluancauhoiType->delete();

        return redirect()->route('admin.tuluancauhoi-types.index')->with('success', 'Xóa loại câu hỏi tự luận thành công.');
    }
}