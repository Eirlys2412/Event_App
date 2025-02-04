<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Models\User;
use App\Modules\Teaching_1\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $active_menu = 'enrollment_list'; // Set active menu
        $enrollments = Enrollment::with(['students', 'phancong.giangvien', 'phancong.hocphan'])->paginate(10);
        return view('Teaching_3::enrollment.index', compact('enrollments', 'active_menu'));
    }

    public function create()
    {
        $active_menu = 'enrollment_add'; // Set active menu
        $students = Student::all();
        $phancongs = PhanCong::with(['giangvien', 'hocphan'])->get();
        return view('Teaching_3::enrollment.create', compact('students', 'phancongs', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'phancong_id' => 'required|exists:phancong,id',
            'timespending' => 'required|numeric|min:0',
            'process' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,success,finished,rejected',
        ]);

        Enrollment::create($request->all());

        return redirect()->route('enrollment.index')->with('success', 'Enrollment created successfully.');
    }

    public function edit($id)
    {
        $active_menu = 'enrollment_edit'; // Set active menu
        $enrollment = Enrollment::findOrFail($id);
        $students = Student::all();
        $phancongs = PhanCong::with(['giangvien', 'hocphan'])->get();
        return view('Teaching_3::enrollment.edit', compact('enrollment', 'students', 'phancongs', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'phancong_id' => 'required|exists:phancong,id',
            'timespending' => 'required|numeric|min:0',
            'process' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,success,finished,rejected',
        ]);

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($request->all());

        return redirect()->route('enrollment.index')->with('success', 'Enrollment updated successfully.');
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return redirect()->route('enrollment.index')->with('success', 'Enrollment deleted successfully.');
    }
}
