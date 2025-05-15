<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Giảng viên mở điểm danh với thời gian cụ thể
    public function startAttendance(Request $request)
{
    $request->validate([
        'tkb_id'     => 'required|integer',
        'duration'   => 'required|integer|min:1',
        'teacher_id' => 'required|integer',
    ]);

    try {
        $tkbId = $request->tkb_id;
        $teacherId = $request->teacher_id;
        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addMinutes($request->duration);

        $phancongId = DB::table('thoi_khoa_bieus')
            ->where('id', $tkbId)
            ->value('phancong_id');

        if (!$phancongId) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phân công cho tkb_id này',
            ], 404);
        }

        $registeredStudents = DB::table('enrollments')
            ->join('phancong', 'enrollments.phancong_id', '=', 'phancong.id')
            ->join('hoc_phans', 'phancong.hocphan_id', '=', 'hoc_phans.id')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('phancong.giangvien_id', $teacherId)
            ->where('phancong.id', $phancongId)
            ->pluck('students.id')
            ->toArray();

        if (empty($registeredStudents)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có sinh viên nào đăng ký học phần này',
            ], 400);
        }

        // Tạo mã QR token duy nhất
        $qrToken = bin2hex(random_bytes(16)); // Tạo chuỗi ngẫu nhiên 32 ký tự

        $studentList = [
            'present' => [],
            'absent'  => $registeredStudents,
        ];

        DB::table('attendances')->updateOrInsert(
            ['tkb_id' => $tkbId],
            [
                'start_time'   => $startTime,
                'end_time'     => $endTime,
                'student_list' => json_encode($studentList),
                'qr_token'     => $qrToken, // Lưu mã QR token
                'updated_at'   => Carbon::now(),
            ]
        );

        // Dữ liệu cho mã QR (có thể mã hóa thành JSON)
        $qrData = json_encode([
            'tkb_id'    => $tkbId,
            'qr_token'  => $qrToken,
            'end_time'  => $endTime->toDateTimeString(),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Mở điểm danh thành công',
            'end_time'     => $endTime->toDateTimeString(),
            'student_list' => $studentList,
            'qr_data'      => $qrData, // Trả về dữ liệu để tạo mã QR
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi mở điểm danh: ' . $e->getMessage(),
        ], 500);
    }
}
    // Sinh viên điểm danh
    public function markAttendance(Request $request)
{
    $request->validate([
        'tkb_id'     => 'required|integer',
        'student_id' => 'required|integer',
        'qr_token'   => 'required|string', // Mã QR token từ sinh viên quét
    ]);

    try {
        $tkbId = $request->tkb_id;
        $studentId = $request->student_id;
        $qrToken = $request->qr_token;

        $attendance = DB::table('attendances')->where('tkb_id', $tkbId)->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa mở điểm danh',
            ], 400);
        }

        $currentTime = Carbon::now();
        if ($currentTime->greaterThan($attendance->end_time)) {
            return response()->json([
                'success' => false,
                'message' => 'Điểm danh đã đóng',
            ], 403);
        }

        // Kiểm tra mã QR token
        if ($attendance->qr_token !== $qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'Mã QR không hợp lệ',
            ], 403);
        }

        $studentList = json_decode($attendance->student_list, true);
        if (!in_array($studentId, $studentList['present'])) {
            $studentList['present'][] = $studentId;
            $studentList['absent'] = array_values(array_diff($studentList['absent'], [$studentId]));
        }

        DB::table('attendances')
            ->where('tkb_id', $tkbId)
            ->update([
                'student_list' => json_encode($studentList),
                'updated_at'   => Carbon::now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Điểm danh thành công',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi điểm danh: ' . $e->getMessage(),
        ], 500);
    }
}
    // Đóng điểm danh và xác định sinh viên vắng
    public function closeAttendance(Request $request)
    {
        $request->validate([
            'tkb_id'      => 'required|integer',
            'student_ids' => 'required|array', // Danh sách toàn bộ sinh viên
        ]);

        try {
            $tkbId = $request->tkb_id;
            $allStudents = $request->student_ids;

            $attendance = DB::table('attendances')->where('tkb_id', $tkbId)->first();
            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy điểm danh',
                ], 404);
            }

            // Xác định sinh viên vắng
            $studentList = json_decode($attendance->student_list, true);
            $presentStudents = $studentList['present'] ?? [];
            $absentStudents = array_values(array_diff($allStudents, $presentStudents));

            $studentList['absent'] = $absentStudents;

            // Cập nhật số lượng sinh viên vắng
            DB::table('attendances')
                ->where('tkb_id', $tkbId)
                ->update([
                    'student_list' => json_encode($studentList),
                    'absent_count' => count($absentStudents),
                    'updated_at'   => Carbon::now(),
                ]);

            return response()->json([
                'success'      => true,
                'message'      => 'Đã đóng điểm danh',
                'absent_count' => count($absentStudents),
                'absent_list'  => $absentStudents,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đóng điểm danh: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Lấy danh sách điểm danh
    public function getAttendanceBySchedule(Request $request)
{
    $request->validate([
        'tkb_id' => 'required|integer',
    ]);

    try {
        $attendance = DB::table('attendances')
            ->where('tkb_id', $request->tkb_id)
            ->first();

        if ($attendance) {
            $studentList = json_decode($attendance->student_list, true);
            $presentIds = $studentList['present'] ?? [];
            $absentIds = $studentList['absent'] ?? [];
            $studentIds = array_merge($presentIds, $absentIds);

            $studentData = [];
            if (!empty($studentIds)) {
                $studentData = DB::table('students')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->whereIn('students.id', $studentIds)
                    ->select('students.id', 'students.mssv', 'users.full_name')
                    ->get()
                    ->map(function ($student) use ($presentIds, $absentIds) {
                        return [
                            'student_id' => $student->id,
                            'mssv'       => $student->mssv,
                            'full_name'  => $student->full_name,
                            'status'     => in_array($student->id, $presentIds) ? 'present' : 'absent',
                        ];
                    })->toArray();
            }

            $currentTime = Carbon::now();
            $endTime = Carbon::parse($attendance->end_time);
            $isOpen = $currentTime->lessThanOrEqualTo($endTime) && $attendance->absent_count === 0;

            // Tạo qr_data giống như trong startAttendance
            $qrData = json_encode([
                'tkb_id'    => $attendance->tkb_id,
                'qr_token'  => $attendance->qr_token,
                'end_time'  => $attendance->end_time,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id'           => $attendance->id,
                    'tkb_id'       => $attendance->tkb_id,
                    'student_list' => $studentData,
                    'start_time'   => $attendance->start_time,
                    'end_time'     => $attendance->end_time,
                    'absent_count' => $attendance->absent_count,
                    'is_open'      => $isOpen,
                    'qr_data'      => $qrData, // Thêm qr_data vào response
                    'created_at'   => $attendance->created_at,
                    'updated_at'   => $attendance->updated_at,
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy điểm danh',
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy dữ liệu điểm danh: ' . $e->getMessage(),
        ], 500);
    }
}
}
