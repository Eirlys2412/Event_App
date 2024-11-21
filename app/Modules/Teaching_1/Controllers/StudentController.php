<?php

namespace App\Modules\Teaching_1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Teaching_1\Models\Student;

class StudentController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeFunction("student_list");

        $active_menu = "student_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách Sinh viên</li>';

        $students = Student::with(['donvi', 'nganh'])->orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Teaching_1::student.index', compact('students', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $this->authorizeFunction("student_add");

        $data['donvis'] = \App\Modules\Teaching_1\Models\Donvi::orderBy('title', 'ASC')->get();
        $data['nganhs'] = \App\Modules\Teaching_1\Models\Nganh::orderBy('title', 'ASC')->get();
        $data['active_menu'] = "student_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('student.index') . '">Sinh viên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo Sinh viên</li>';

        return view('Teaching_1::student.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeFunction("student_add");

        $this->validateRequest($request);

        $data = $request->all();
        $data['slug'] = $this->generateUniqueSlug(Str::slug($request->input('mssv')));

        $student = Student::create($data);

        return $student
            ? redirect()->route('student.index')->with('success', 'Tạo sinh viên thành công!')
            : back()->with('error', 'Có lỗi xảy ra!');
    }

    public function edit(string $id)
    {
        $this->authorizeFunction("student_edit");

        $donvis = \App\Modules\Teaching_1\Models\Donvi::orderBy('title', 'ASC')->get();
        $nganhs = \App\Modules\Teaching_1\Models\Nganh::orderBy('title', 'ASC')->get();
        $student = Student::findOrFail($id);

        $active_menu = "student_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('student.index') . '">Sinh viên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Sinh viên</li>';

        return view('Teaching_1::student.edit', compact('breadcrumb', 'student', 'active_menu', 'donvis', 'nganhs'));
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeFunction("student_edit");

        $student = Student::findOrFail($id);
        $this->validateRequest($request, $student->id); // Truyền id vào validate

        $data = $request->all();
        $data['slug'] = $this->generateUniqueSlug(Str::slug($request->input('mssv')), $student->id);

        $student->fill($data)->save();

        return redirect()->route('student.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(string $id)
    {
        $this->authorizeFunction("student_delete");

        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('student.index')->with('success', 'Xóa sinh viên thành công!');
    }

    public function studentStatus(Request $request)
    {
        $func = "student_edit";
        if (!$this->check_function($func)) {
            return response()->json(['msg' => "Bạn không có quyền cập nhật trạng thái sinh viên", 'status' => false]);
        }

        $status = $request->mode == 'true' ? 'đang học' : 'thôi học';
        DB::table('students')->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['msg' => "Cập nhật trạng thái sinh viên thành công", 'status' => true]);
    }

    protected function validateRequest(Request $request, $studentId = null)
    {
        $request->validate([
            'mssv' => 'string|required', // Không cần kiểm tra duy nhất cho mssv nữa
            'donvi_id' => 'numeric|required',
            'nganh_id' => 'numeric|required',
            'khoa' => 'string|required',
            'status' => 'required|in:đang học,thôi học,tốt nghiệp',
            'user_id' => 'numeric|required|unique:students,user_id,' . $studentId, // Kiểm tra tính duy nhất của user_id
        ]);
    }


    protected function generateUniqueSlug($slug, $existingId = null)
    {
        $slugCount = Student::where('slug', $slug)
            ->when($existingId, function ($query) use ($existingId) {
                return $query->where('id', '!=', $existingId);
            })
            ->count();

        return $slugCount > 0 ? $slug . '-' . uniqid() : $slug;
    }

    protected function authorizeFunction($func)
    {
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
    }
}
