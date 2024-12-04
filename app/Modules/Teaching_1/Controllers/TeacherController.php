<?php

namespace App\Modules\Teaching_1\Controllers;
use App\Http\Controllers\Controller;

use App\Modules\Teaching_1\Models\Teacher;
use App\Modules\Teaching_1\Models\DonVi;
use App\Modules\Teaching_1\Models\ChuyenNganh;
use App\Modules\Teaching_1\Controllers\DonviController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    // Hiển thị danh sách giảng viên
    public function index()
    {
        $active_menu = 'teacher_list';
        $chuyen_nganh = ChuyenNganh::all(); // Lấy danh sách chuyên ngành từ bảng liên quan

        // Nạp trước dữ liệu liên kết bằng with() và sử dụng paginate để phân trang
        $teachers = Teacher::with(['donVi', 'user', 'chuyenNganhs'])->paginate(10);
        return view('Teaching_1::teacher.index', compact('teachers','chuyen_nganh', 'active_menu'));
    }

    // Hiển thị form thêm mới giảng viên
    public function create()
    {
        $active_menu = 'teacher_add';
        $donVis = Donvi::all(); // Lấy tất cả đơn vị để chọn
        $users = User::all(); // Lấy tất cả người dùng để chọn
        $chuyenNganhs = ChuyenNganh::all(); // Lấy tất cả chuyên ngành để chọn
        return view('Teaching_1::teacher.create', compact('active_menu', 'users','donVis','chuyenNganhs'));
    }

    // Lưu thông tin giảng viên mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mgv' => 'required|string|max:50|unique:teacher',
            'ma_donvi' => 'required|exists:donvi,id',
            'user_id' => 'required|exists:users,id',
            'chuyen_nganh' => 'required|exists:chuyennganhs,id',
            'hoc_ham' => 'nullable|string|max:255',
            'hoc_vi' => 'nullable|string|max:255',
            'loai_giangvien' => 'nullable|string|max:255',
        ]);

        Teacher::create($validatedData);

        return redirect()->route('admin.teacher.index')->with('success', 'Giảng viên đã được thêm thành công.');
    }

    // Hiển thị thông tin chi tiết của một giảng viên
    public function show(Teacher $teacher)
    {
        $active_menu = 'teacher_show';
        return view('Teaching_1::teacher.show', compact('teacher', 'active_menu'));
    }

    // Hiển thị form chỉnh sửa thông tin giảng viên
    public function edit(Teacher $teacher)
    {
        $active_menu = 'teacher_edit';
        $donVis = DonVi::all(); // Lấy tất cả đơn vị để chọn
        $users = User::all(); // Lấy tất cả người dùng để chọn
        $chuyenNganhs = ChuyenNganh::all(); // Lấy tất cả chuyên ngành để chọn

        return view('Teaching_1::teacher.edit', compact('teacher', 'active_menu', 'users','donVis','chuyenNganhs'));
    }

    // Cập nhật thông tin giảng viên
    public function update(Request $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'mgv' => 'required|string|max:50|unique:teacher,mgv,' . $teacher->id,
            'ma_donvi' => 'required|exists:donvi,id',
            'user_id' => 'required|exists:users,id',
            'chuyen_nganh' => 'required|exists:chuyennganhs,id',
            'hoc_ham' => 'nullable|string|max:255',
            'hoc_vi' => 'nullable|string|max:255',
            'loai_giangvien' => 'nullable|string|max:255',
        ]);

        $teacher->update($validatedData);

        return redirect()->route('admin.teacher.index')->with('success', 'Thông tin giảng viên đã được cập nhật thành công.');
    }

    // Xóa giảng viên
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teacher.index')->with('success', 'Giảng viên đã được xóa thành công.');
    }

    // Tìm kiếm giảng viên theo mã giảng viên
    public function search(Request $request)
    {
        $active_menu = 'teacher_list';
        $chuyen_nganh = ChuyenNganh::all();
        $search = $request->input('datasearch');

        $teachers = Teacher::with(['donVi', 'user', 'chuyenNganhs'])
            ->where('mgv', 'LIKE', "%{$search}%")
            ->paginate(10);

        return view('Teaching_1::teacher.index', compact('teachers', 'chuyen_nganh', 'active_menu', 'search'));
    }
}


