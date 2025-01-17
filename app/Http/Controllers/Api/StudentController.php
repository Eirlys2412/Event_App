<?php

namespace App\Http\Controllers\Api;
use App\Modules\Teaching_1\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class StudentController extends Controller
{
    // 1. Lấy thông tin sinh viên theo user_id
public function show($userId)
{
    $user = Auth::user(); // Lấy thông tin người dùng từ token

    // Tìm sinh viên theo user_id
    $student = Student::where('user_id', $userId)->first();

    if (!$student) {
        return response()->json(null, 200); // Trả về null
    }

    return response()->json([
        'authenticated_user' => $user, // Người dùng đang xác thực
        'data' => $student
    ]);
}



// 2. Cập nhật thông tin sinh viên theo user_id
public function update(Request $request, $userId)
{
    // Tìm sinh viên theo user_id
    $student = Student::where('user_id', $userId)->first();

    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    // Validate dữ liệu gửi lên
    $validatedData = $request->validate([
        'mssv' => 'required|string|max:255|unique:students,mssv,' . $student->id,
        'donvi_id' => 'required|integer|exists:donvi,id', // Kiểm tra ID đơn vị có tồn tại
        'nganh_id' => 'required|integer|exists:nganh,id', // Kiểm tra ID ngành có tồn tại
        'khoa' => 'required|string|max:4', // Ví dụ: "2021"
    ]);

    // Tự động gán giá trị slug từ mssv
    $validatedData['slug'] = $validatedData['mssv'];

    // Cập nhật thông tin
    $student->update($validatedData);

    return response()->json([
        'message' => 'Student updated successfully',
        'data' => $student
    ]);
}


}
