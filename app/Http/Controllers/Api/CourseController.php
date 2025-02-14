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
        ->join('classes', 'phancong.class_id', '=', 'classes.id')
        ->join('program_details', 'phancong.hocphan_id', '=', 'program_details.hocphan_id')
        ->join('chuong_trinh_dao_tao', 'program_details.chuongtrinh_id', '=', 'chuong_trinh_dao_tao.id')
        ->join('nganh', 'chuong_trinh_dao_tao.nganh_id', '=', 'nganh.id')
        ->join('teacher', 'phancong.giangvien_id', '=', 'teacher.id')
        ->join('users', 'teacher.user_id', '=', 'users.id')
        ->join('hoc_ky', 'program_details.hoc_ky_id', '=', 'hoc_ky.id')  // Thêm join với bảng hoc_ky
        ->select(
            'phancong.id as phancong_id',  // Thêm phancong_id để trả về
            'hoc_phans.title', 
            'hoc_phans.code', 
            'hoc_phans.tinchi', 
            'classes.class_name as class_course',  // Thay đổi tên cột class_course
            'phancong.max_student',
            'users.full_name as teacher_name',
            'hoc_ky.so_hoc_ky',           
            'program_details.loai'        // Loại học phần (Bắt buộc/Tự chọn)
        )
        ->where('nganh.id', $studentMajor)
        ->get();

    return response()->json($courses);
}




public function enrollCourse(Request $request)
{
    // Kiểm tra request có đầy đủ thông tin không
    if (!$request->phancong_id || !$request->student_id) {
        return response()->json(['message' => 'Missing phancong_id or student_id'], 400);
    }

    // Kiểm tra nếu sinh viên đã đăng ký học phần này
    $existingEnrollment = DB::table('enrollments')
        ->where('student_id', $request->student_id)
        ->where('phancong_id', $request->phancong_id)
        ->first();

    if ($existingEnrollment) {
        return response()->json(['message' => 'Học phần này đã đăng kí'], 400);
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

            // Xác định các học phần chưa hoàn thành
            $missingCourses = array_diff($requiredCourses, $completedCourses);

            if (!empty($missingCourses)) {
                // Lấy tên của các học phần còn thiếu
                $missingCourseNames = DB::table('hoc_phans')
                    ->whereIn('id', $missingCourses)
                    ->pluck('title')
                    ->toArray();

                return response()->json([
                    'message' => 'Prerequisites not met',
                    'missing_courses' => $missingCourseNames,
                ], 400);
            }
        }
    }

    // Đăng ký học phần
    DB::table('enrollments')->insert([
        'student_id' => $request->student_id,
        'phancong_id' => $request->phancong_id,
        'timespending' => $request->timespending ?? 0,
        'process' => $request->process ?? 0,
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Enrollment successful'], 201);
}

public function getEnrolledCourses(Request $request)
{
    if (!$request->student_id) {
        return response()->json(['message' => 'Missing student_id'], 400);
    }

    $courses = DB::table('enrollments')
        ->join('phancong', 'enrollments.phancong_id', '=', 'phancong.id')
        ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
        ->join('classes', 'phancong.class_id', '=', 'classes.id')
        ->join('teacher', 'phancong.giangvien_id', '=', 'teacher.id')
        ->join('users', 'teacher.user_id', '=', 'users.id')
        ->select(
            'enrollments.id as enrollment_id',
            'hoc_phans.title as title',
            'hoc_phans.tinchi as tinchi',
            'hoc_phans.code as course_code',
            'classes.class_name as class_course',
            'users.full_name as teacher_name',
            'enrollments.status',
            'enrollments.created_at'
        )
        ->where('enrollments.student_id', $request->student_id)
        ->get();

    return response()->json($courses);
}

public function deleteEnrollment(Request $request)
{
    if (!$request->enrollment_id) {
        return response()->json(['message' => 'Missing enrollment_id'], 400);
    }

    $deleted = DB::table('enrollments')
        ->where('id', $request->enrollment_id)
        ->delete();

    if ($deleted) {
        return response()->json(['message' => 'Enrollment deleted successfully'], 200);
    } else {
        return response()->json(['message' => 'Enrollment not found or already deleted'], 404);
    }
}

public function searchCourses(Request $request)
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

    // Lấy từ khóa tìm kiếm từ request
    $keyword = $request->keyword;

    // Lấy các học phần liên quan đến ngành của sinh viên, có lọc theo keyword
    $courses = DB::table('phancong')
        ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
        ->join('classes', 'phancong.class_id', '=', 'classes.id')
        ->join('program_details', 'phancong.hocphan_id', '=', 'program_details.hocphan_id')
        ->join('chuong_trinh_dao_tao', 'program_details.chuongtrinh_id', '=', 'chuong_trinh_dao_tao.id')
        ->join('nganh', 'chuong_trinh_dao_tao.nganh_id', '=', 'nganh.id')
        ->join('teacher', 'phancong.giangvien_id', '=', 'teacher.id')
        ->join('users', 'teacher.user_id', '=', 'users.id')
        ->join('hoc_ky', 'program_details.hoc_ky_id', '=', 'hoc_ky.id')
        ->select(
            'phancong.id as phancong_id',
            'hoc_phans.title',
            'hoc_phans.code',
            'hoc_phans.tinchi',
            'classes.class_name as class_course',
            'phancong.max_student',
            'users.full_name as teacher_name',
            'hoc_ky.so_hoc_ky',
            'program_details.loai'
        )
        ->where('nganh.id', $studentMajor)
        ->when($keyword, function ($query, $keyword) {
            // Tìm kiếm theo tên học phần, mã học phần hoặc tên giảng viên
            $query->where('hoc_phans.title', 'like', "%$keyword%")
                  ->orWhere('hoc_phans.code', 'like', "%$keyword%");
        })
        ->get();

    return response()->json($courses);
}


// Thời khoá biểu
public function getTimetable(Request $request)
{
    if (!$request->student_id) {
        return response()->json(['message' => 'Missing student_id'], 400);
    }

    try {
        // Truy vấn thời khóa biểu với các thông tin liên quan
        $timetable = DB::table('thoi_khoa_bieus')
            ->join('phancong', 'thoi_khoa_bieus.phancong_id', '=', 'phancong.id')
            ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
            ->join('enrollments', 'phancong.id', '=', 'enrollments.phancong_id')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->join('classes', 'phancong.class_id', '=', 'classes.id')
            ->join('dia_diem', 'thoi_khoa_bieus.diadiem_id', '=', 'dia_diem.id')
            ->join('teacher', 'phancong.giangvien_id', '=', 'teacher.id')
            ->join('users', 'teacher.user_id', '=', 'users.id')
            ->select(
                'thoi_khoa_bieus.id as timetable_id',
                'hoc_phans.title',
                'thoi_khoa_bieus.buoi',
                'thoi_khoa_bieus.ngay',
                'thoi_khoa_bieus.tietdau',
                'thoi_khoa_bieus.tietcuoi',
                'dia_diem.title as location',
                'classes.class_name as class_course',
                'users.full_name as teacher_name'
            )
            ->where('students.id', $request->student_id)
            ->orderBy('thoi_khoa_bieus.ngay', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Danh sách thời khóa biểu',
            'data' => $timetable
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy thời khóa biểu: ' . $e->getMessage()
        ], 500);
    }
}

public function getClassStudents(Request $request)
    {
        $teacherId = $request->input('teacher_id');

        $students = DB::table('students')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('teacher', 'classes.teacher_id', '=', 'teacher.id')
            ->join('users', 'students.user_id', '=', 'users.id')  // Join để lấy tên sinh viên
            ->select(
                'students.id as student_id',
                'students.mssv',
                'users.full_name as student_name',
                'classes.class_name',
                'classes.description',
                'students.khoa',
                'students.status'
            )
            ->where('classes.teacher_id', $teacherId)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $students
        ], 200);
    }


    public function getStudentCourses(Request $request)
{
    $studentId = $request->input('student_id');  // Thêm tham số student_id để lọc theo sinh viên
    
    // Lấy danh sách học phần sinh viên đã đăng ký trong lớp của giảng viên
    $studentCourses = DB::table('enrollments')
        ->join('phancong', 'enrollments.phancong_id', '=', 'phancong.id')
        ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
        ->join('students', 'enrollments.student_id', '=', 'students.id')
        ->join('classes', 'students.class_id', '=', 'classes.id')
        ->join('teacher', 'classes.teacher_id', '=', 'teacher.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->select(
            'students.id as student_id',
            'users.full_name as student_name',
            'students.mssv',
            'hoc_phans.title as course_title',
            'hoc_phans.code as course_code',
            'hoc_phans.tinchi as credits',
            'classes.class_name as class_course',
            'enrollments.status as enrollment_status',
            'enrollments.created_at as enrollment_date'
        )
        ->where('students.id', $studentId)  // Lọc theo student_id
        ->orderBy('enrollments.created_at', 'desc')
        ->get();
    
    if ($studentCourses->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không tìm thấy học phần nào cho sinh viên này.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $studentCourses
    ], 200);
}

    

}
