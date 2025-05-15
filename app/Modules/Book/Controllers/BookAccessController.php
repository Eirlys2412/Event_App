<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Book\Models\BookAccess;
use App\Modules\Book\Models\Book;
use Illuminate\Http\Request;

class BookAccessController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20); 
        $this->middleware('auth'); 
    }

    // Hiển thị danh sách BookAccess
    public function index()
    {
        $bookAccesses = BookAccess::with('book')->paginate($this->pagesize); // Lấy tất cả BookAccess và thông tin liên quan tới Book
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Danh sách điểm truy cập sách</li>';
        $active_menu = "bookaccess_list";

        return view('Book::bookaccess.index', compact('bookAccesses', 'breadcrumb', 'active_menu'));
    }

    // Hiển thị form tạo mới BookAccess
    public function create()
    {
        $books = Book::all(); // Lấy tất cả các sách để chọn
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Thêm điểm truy cập sách</li>';
        $active_menu = "bookaccess_add";

        return view('Book::bookaccess.create', compact('books', 'breadcrumb', 'active_menu'));
    }

    // Lưu BookAccess mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id', // Đảm bảo book_id tồn tại trong bảng books
            'point_access' => 'required|integer', // Điểm truy cập là một số nguyên
        ]);

        // Tạo mới BookAccess
        BookAccess::create($request->only('book_id', 'point_access'));

        return redirect()->route('admin.bookaccess.index')->with('success', 'Thêm điểm truy cập sách thành công.');
    }

    // Hiển thị form chỉnh sửa BookAccess
    public function edit($id)
    {
        $bookAccess = BookAccess::findOrFail($id); // Tìm BookAccess theo ID
        $books = Book::all(); // Lấy danh sách sách để người dùng có thể chọn
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Chỉnh sửa điểm truy cập sách</li>';
        $active_menu = "bookaccess_edit";

        return view('Book::bookaccess.edit', compact('bookAccess', 'books', 'breadcrumb', 'active_menu'));
    }

    // Cập nhật BookAccess trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $bookAccess = BookAccess::findOrFail($id); // Tìm BookAccess theo ID

        $request->validate([
            'book_id' => 'required|exists:books,id', // Đảm bảo book_id tồn tại trong bảng books
            'point_access' => 'required|integer', // Điểm truy cập là một số nguyên
        ]);

        // Cập nhật BookAccess
        $bookAccess->update($request->only('book_id', 'point_access'));

        return redirect()->route('admin.bookaccess.index')->with('success', 'Cập nhật điểm truy cập sách thành công.');
    }

    // Xóa BookAccess
    public function destroy($id)
    {
        $bookAccess = BookAccess::findOrFail($id); // Tìm BookAccess theo ID
        $bookAccess->delete(); // Xóa BookAccess

        return redirect()->route('admin.bookaccess.index')->with('success', 'Xóa điểm truy cập sách thành công.');
    }
}
