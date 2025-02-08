<?php

namespace App\Modules\Teaching_1\Controllers;

use Illuminate\Http\Request;
use App\Modules\Teaching_1\Models\ClassModel;
use App\Http\Controllers\Controller;
use App\Modules\Teaching_1\Models\Nganh;
use App\Modules\Teaching_1\Models\Teacher;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    // Hiển thị danh sách các lớp
    public function index()
    {
        $active_menu = 'class_list';
        $classes = ClassModel::with(['teacher', 'nganh'])->paginate(10);
        
        return view('Teaching_1::class.index', compact('classes', 'active_menu'));
    }

    // Hiển thị form tạo lớp học mới
    public function create()
    {
        $active_menu = 'class_add';
        $teachers = Teacher::all();
        $nganhs = Nganh::all();
        
        return view('Teaching_1::class.create', compact('active_menu', 'teachers', 'nganhs'));
    }

    // Lưu lớp học mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teacher,id',
            'nganh_id' => 'nullable|exists:nganh,id',
            'description' => 'nullable|string',
            'max_students' => 'required|integer|min:1',
        ]);

        ClassModel::create($validatedData);

        return redirect()->route('admin.class.index')->with('success', 'Lớp học đã được thêm thành công.');
    }

    // Hiển thị chi tiết một lớp học
    public function show(ClassModel $class)
    {
        $active_menu = 'class_show';
        return view('Teaching_1::class.show', compact('class', 'active_menu'));
    }

    // Hiển thị form chỉnh sửa lớp học
    public function edit(ClassModel $class)
    {
        $active_menu = 'class_edit';
        $teachers = Teacher::all();
        $nganhs = Nganh::all();

        return view('Teaching_1::class.edit', compact('class', 'active_menu', 'teachers', 'nganhs'));
    }

    // Cập nhật thông tin lớp học
    public function update(Request $request, ClassModel $class)
    {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teacher,id',
            'nganh_id' => 'nullable|exists:nganh,id',
            'description' => 'nullable|string',
            'max_students' => 'required|integer|min:1',
        ]);

        $class->update($validatedData);

        return redirect()->route('admin.class.index')->with('success', 'Thông tin lớp học đã được cập nhật thành công.');
    }

    // Xóa lớp học
    public function destroy(ClassModel $class)
    {
        $class->delete();
        return redirect()->route('admin.class.index')->with('success', 'Lớp học đã được xóa thành công.');
    }

    // Tìm kiếm lớp học theo tên
    public function search(Request $request)
    {
        $active_menu = 'class_list';
        $search = $request->input('datasearch');

        $classes = ClassModel::with(['teacher', 'nganh'])
            ->where('class_name', 'LIKE', "%{$search}%")
            ->paginate(10);

        return view('Teaching_1::class.index', compact('classes', 'active_menu', 'search'));
    }
}
