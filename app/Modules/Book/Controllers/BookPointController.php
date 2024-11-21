<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Book\Models\BookPoint;
use Illuminate\Http\Request;

class BookPointController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    // Hiển thị danh sách BookPoint
    public function index()
    {
        $bookPoints = BookPoint::paginate($this->pagesize);
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Danh sách hành động</li>';
        $active_menu = "bookpoint_list";

        return view('Book::bookpoints.index', compact('bookPoints', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới BookPoint
    public function create()
    {
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Thêm hành động</li>';
        $active_menu = "bookpoint_add";

        return view('Book::bookpoints.create', compact('breadcrumb', 'active_menu'));
    }

    // Lưu BookPoint mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'func_cmd' => 'required|string|max:255',
            'point' => 'required|integer',
        ]);

        BookPoint::create($request->only('func_cmd', 'point'));

        return redirect()->route('admin.bookpoints.index')->with('success', 'Thêm hành động thành công.');
    }

    // Hiển thị form chỉnh sửa BookPoint
    public function edit($id)
    {
        $bookPoint = BookPoint::findOrFail($id);
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Chỉnh sửa hành động</li>';
        $active_menu = "bookpoint_edit";

        return view('Book::bookpoints.edit', compact('bookPoint', 'breadcrumb', 'active_menu'));
    }

    // Cập nhật BookPoint trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $bookPoint = BookPoint::findOrFail($id);

        $request->validate([
            'func_cmd' => 'required|string|max:255',
            'point' => 'required|integer',
        ]);

        $bookPoint->update($request->only('func_cmd', 'point'));

        return redirect()->route('admin.bookpoints.index')->with('success', 'Cập nhật hành động thành công.');
    }

    // Xóa BookPoint
    public function destroy($id)
    {
        $bookPoint = BookPoint::findOrFail($id);
        $bookPoint->delete();

        return redirect()->route('admin.bookpoints.index')->with('success', 'Xóa hành động thành công.');
    }
    
}
