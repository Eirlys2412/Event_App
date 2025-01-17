<?php

namespace App\Http\Controllers\Api;
use App\Modules\Teaching_1\Models\Teacher;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class TeacherController extends Controller
{
    // 1. Lấy thông tin giảng viên theo user_id
public function show($userId)
{
    $user = Auth::user(); // Lấy thông tin người dùng từ token

    // Tìm giảng viên theo user_id
    $teacher = Teacher::where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json(null, 200); // Trả về null
    }

    return response()->json([
        'authenticated_user' => $user, // Người dùng đang xác thực
        'data' => $teacher
    ]);
}



// 2. Cập nhật thông tin giảng viên theo user_id
public function update(Request $request, $userId)
{
    // Tìm giảng viên theo user_id
    $teacher = Teacher::where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json(['message' => 'teacher not found'], 404);
    }

    // Validate dữ liệu gửi lên
    $validatedData = $request->validate([
        'mgv' => 'required|string|max:255|unique:teacher,mgv,' . $teacher->id,
        'ma_donvi' => 'required|integer|exists:donvi,id', // Kiểm tra ID đơn vị có tồn tại
        'chuyen_nganh' => 'required|integer|exists:chuyennganhs,id', // Kiểm tra ID ngành có tồn tại
        'hoc_ham' => 'required|string|max:255', 
        'hoc_vi' => 'required|string|max:255', 
        'loai_giangvien' => 'required|string|max:255', 
    ]);

    // Tự động gán giá trị slug từ mssv
    $validatedData['slug'] = $validatedData['mgv'];

    // Cập nhật thông tin
    $teacher->update($validatedData);

    return response()->json([
        'message' => 'teacher updated successfully',
        'data' => $teacher
    ]);
}


}
