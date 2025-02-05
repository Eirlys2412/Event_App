<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use app\Modules\Teaching_3\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{

    public function getAvailableCourses(Request $request)
{
    // Kiểm tra student_id có được gửi trong request không
    if (!$request->student_id) {
        return response()->json(['message' => 'Missing student_id'], 400);
    }

    // Lấy ngành của sinh viên
    $studentMajor = DB::table('students')
        ->join('nganh', 'students.nganh_id', '=', 'nganh.id')
        ->where('students.id', $request->student_id)
        ->value('nganh.id');

    if (!$studentMajor) {
        return response()->json(['message' => 'Student major not found'], 404);
    }

    // Lấy các học phần liên quan đến ngành của sinh viên
    $courses = DB::table('phancong')
        ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
        ->join('program_details', 'phancong.hocphan_id', '=', 'program_details.hocphan_id')
        ->join('chuong_trinh_dao_tao', 'program_details.chuongtrinh_id', '=', 'chuong_trinh_dao_tao.id')
        ->join('nganh', 'chuong_trinh_dao_tao.nganh_id', '=', 'nganh.id')
        ->join('teacher', 'phancong.giangvien_id', '=', 'teacher.id')
        ->join('users', 'teacher.user_id', '=', 'users.id')
        ->select(
            'hoc_phans.title', 
            'hoc_phans.code', 
            'hoc_phans.tinchi', 
            'phancong.class_course', 
            'phancong.max_student',
            'users.full_name as teacher_name'
        )
        ->where('nganh.id', $studentMajor)  // Chỉ lấy học phần thuộc ngành của sinh viên
        ->get();

    return response()->json($courses);
}


public function enrollCourse(Request $request)
{
    // Kiểm tra request có đầy đủ thông tin không
    if (!$request->phancong_id || !$request->student_id) {
        return response()->json(['message' => 'Missing phancong_id or student_id'], 400);
    }

    // Lấy thông tin phân công học phần
    $phancong = DB::table('phancong')->where('id', (int) $request->phancong_id)->first();

    Log::info('phancong_id received: ' . $request->phancong_id);
    // Kiểm tra nếu không tìm thấy học phần
    if (!$phancong) {
        return response()->json(['message' => 'Course assignment not found'], 404);
    }

    // Kiểm tra số lượng sinh viên đã đăng ký
    $currentEnrollments = DB::table('enrollments')->where('phancong_id', $request->phancong_id)->count();

    if ($currentEnrollments >= $phancong->max_student) {
        return response()->json(['message' => 'Course is full'], 400);
    }

    // Lấy điều kiện tiên quyết từ bảng program_details
    $prerequisiteData = DB::table('program_details')
        ->where('hocphan_id', $phancong->hocphan_id)
        ->value('hocphantienquyet');

    if ($prerequisiteData) {
        $prerequisiteArray = json_decode($prerequisiteData, true);

        if (isset($prerequisiteArray['next']) && is_array($prerequisiteArray['next'])) {
            $requiredCourses = $prerequisiteArray['next'];

            // Lấy danh sách học phần sinh viên đã hoàn thành
            $completedCourses = DB::table('enrollments')
                ->join('phancong', 'enrollments.phancong_id', '=', 'phancong.id')
                ->where('enrollments.student_id', $request->student_id)
                ->where('enrollments.status', 'finished')
                ->pluck('phancong.hocphan_id')
                ->toArray();

            if (array_diff($requiredCourses, $completedCourses)) {
                return response()->json(['message' => 'Prerequisites not met'], 400);
            }
        }
    }

    // Đăng ký học phần
    $enrollment = DB::table('enrollments')->insert([
        'student_id' => $request->student_id,
        'phancong_id' => $request->phancong_id,
        'timespending' => $request->timespending ?? 0,
        'process' => $request->process ?? 'in_progress',
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json(['message' => 'Enrollment successful'], 201);
}


}
