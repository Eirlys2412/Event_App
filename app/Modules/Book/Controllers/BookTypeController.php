<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Book\Models\BookType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    // Hiển thị danh sách loại sách
    public function index()
    {
        $bookTypes = BookType::paginate($this->pagesize);
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Danh sách loại sách</li>';
        $active_menu = "booktype_list";

        return view('Book::types.index', compact('bookTypes', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới loại sách
    public function create()
    {
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Thêm loại sách</li>';
        $active_menu = "booktype_add";

        return view('Book::types.create', compact('breadcrumb', 'active_menu'));
    }

    // Lưu loại sách mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:book_types',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($request->title);
        $existingSlug = BookType::where('slug', $slug)->first();

        if ($existingSlug) {
            $slug = $slug . '-' . time(); 
        }
        BookType::create([
            'title' => $request->title,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.booktypes.index')->with('success', 'Tạo loại sách thành công.');
    }


    // Hiển thị form chỉnh sửa loại sách
    public function edit($id)
    {
        $bookType = BookType::findOrFail($id);
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Chỉnh sửa loại sách</li>';
        $active_menu = "booktype_edit";

        return view('Book::types.edit', compact('bookType', 'breadcrumb', 'active_menu'));
    }

    // Cập nhật loại sách trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $bookType = BookType::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:book_types,title,' . $bookType->id,
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($request->title);
        $existingSlug = BookType::where('slug', $slug)->where('id', '!=', $bookType->id)->first();

        if ($existingSlug) {
            $slug = $slug . '-' . time(); 
        }

        $bookType->update([
            'title' => $request->title,
            'slug' => $slug,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.booktypes.index')->with('success', 'Cập nhật loại sách thành công.');
    }

    // Xóa loại sách
    public function destroy($id)
    {
        $bookType = BookType::findOrFail($id);
        $bookType->delete();

        return redirect()->route('admin.booktypes.index')->with('success', 'Xóa loại sách thành công.');
    }
    public function bookTypeStatus(Request $request)
    {
        
        $status = $request->mode == 'true' ? 'active' : 'inactive';
        DB::table('book_types')->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['msg' => "Cập nhật thành công", 'status' => true]);
    }
}
