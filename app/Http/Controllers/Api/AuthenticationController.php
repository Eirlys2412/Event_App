<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
class AuthenticationController extends Controller
{
    public function savenewUser(Request $request) 
{
    $this->validate($request, [
        'full_name' => 'string|required',
        'description' => 'string|nullable',
        // 'phone' => 'string|required',
        'email' => 'string|required|email',
        'password' => 'string|required',
        'role' => 'string|required|in:student,teacher',
    ]);

    $data = $request->all();

    // // Kiểm tra số điện thoại đã tồn tại
    // if (\App\Models\User::where('phone', $data['phone'])->exists()) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Số điện thoại đã tồn tại',
    //     ], 200);
    // }

    // Kiểm tra email đã tồn tại
    if (\App\Models\User::where('email', $data['email'])->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Email đã tồn tại',
        ], 200);
    }

    // Gán các giá trị mặc định
    $data['photo'] = asset('backend/images/profile-6.jpg');
    $data['password'] = Hash::make($data['password']);
    // $data['username'] = $data['phone'];

    // Tạo user
    $user = \App\Models\User::create($data);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Đăng ký thất bại',
        ], 401);
    }

    // Tạo token cho người dùng mới
    $token =  $user->createToken('appToken')->accessToken;
    // Trả về token và thông tin người dùng
    return response()->json([
        'success' => true,
        'message' => 'Đăng ký thành công',
        'user' => [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role,
            'photo' => $user->photo,
        ],
        'token' => $token, // Token được trả về
    ], 200);
}

    
public function store()
{
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
        // Successful authentication
        $user = User::with(['student', 'teacher'])->find(Auth::user()->id);

        if ($user->status == 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        } else {
            $user_token['token'] = $user->createToken('appToken')->accessToken;

            // Get student_id and teacher_id if they exist
            $studentId = $user->student ? $user->student->id : 0;
            $teacherId = $user->teacher ? $user->teacher->id : 0;

            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
                'student_id' => $studentId,
                'teacher_id' => $teacherId, // Return teacher_id
            ], 200);
        }
    } else {
        // Failure to authenticate
        return response()->json([
            'success' => false,
            'message' => 'Failed to authenticate.',
        ], 401);
    }
}




public function createStudent(Request $request)
{
    $this->validate($request, [
        'mssv' => 'string|required|unique:students,mssv',
        'donvi_id' => 'integer|required|exists:donvi,id',
        'nganh_id' => 'integer|required|exists:nganh,id',
        'class_id' => 'integer|required|exists:classes,id',
        'khoa' => 'string|required',
        'user_id' => 'integer|required|exists:users,id',
    ]);

    try {
        $data = $request->all();

        // Tạo slug từ MSSV
        $data['slug'] = Str::slug($data['mssv'], '-');

        // Thêm dữ liệu vào bảng students
        $student = \App\Modules\Teaching_1\Models\Student::create([
            'mssv' => $data['mssv'],
            'donvi_id' => $data['donvi_id'],
            'nganh_id' => $data['nganh_id'],
            'class_id' => $data['class_id'],
            'khoa' => $data['khoa'],
            'user_id' => $data['user_id'],
            'slug' => $data['slug'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student,
        ], 201); // Trả về mã trạng thái 201 khi tạo thành công
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create student',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function createTeacher(Request $request)
{
    $this->validate($request, [
        'mgv' => 'string|required|unique:teacher,mgv', // Mã giảng viên phải là duy nhất
        'ma_donvi' => 'integer|required|exists:donvi,id', // Mã đơn vị phải tồn tại
        'user_id' => 'integer|required|exists:users,id', // user_id phải tồn tại
        'chuyen_nganh' => 'integer|required', // Chuyên ngành là bắt buộc
        'hoc_ham' => 'string|nullable', // Học hàm (nếu có)
        'hoc_vi' => 'string|nullable', // Học vị (nếu có)
        'loai_giangvien' => 'string|required', // Loại giảng viên chỉ chấp nhận full-time hoặc part-time
    ]);

    try {
        $data = $request->all();

        // Tạo slug từ mã giảng viên
        $data['slug'] = Str::slug($data['mgv'], '-');

        // Thêm dữ liệu vào bảng teachers
        $teacher = \App\Modules\Teaching_1\Models\Teacher::create([
            'mgv' => $data['mgv'],
            'ma_donvi' => $data['ma_donvi'],
            'user_id' => $data['user_id'],
            'chuyen_nganh' => $data['chuyen_nganh'],
            'hoc_ham' => $data['hoc_ham'] ?? null,
            'hoc_vi' => $data['hoc_vi'] ?? null,
            'loai_giangvien' => $data['loai_giangvien'],
            'slug' => $data['slug'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully',
            'data' => $teacher,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create teacher',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function googleSignIn(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'full_name' => 'required|string',
        'google_id' => 'required|string',
        'role' => 'nullable|string|in:student,teacher',
        'budget' => 'nullable|numeric',
    ]);

    // Kiểm tra nếu user đã tồn tại hoặc tạo mới
    $user = User::firstOrCreate(
        ['email' => $request->email],
        [
            'full_name' => $request->full_name,
            'google_id' => $request->google_id,
            'username' => $request->input('username', null),
            'role' => $request->input('role', 'student'),
            'budget' => $request->input('budget', 0),
            'phone' => $request->input('phone', null),
            'password' => bcrypt('default_password'),
            'status' => 'active',
            'photo' => 'backend/images/profile-6.jpg',
            'remember_token' => Str::random(60),
        ]
    );

    // Lấy thông tin student_id và teacher_id nếu có
    $user->load(['student', 'teacher']); // Eager load để tránh query thừa

    $studentId = $user->student ? $user->student->id : 0;
    $teacherId = $user->teacher ? $user->teacher->id : 0;

    // Tạo token cho user
    $token = $user->createToken('GoogleSignIn')->accessToken;

    return response()->json([
       'user' => [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role,
            'photo' => $user->photo,
        ],
        'student_id' => $studentId,
        'teacher_id' => $teacherId,
        'token' => $token,
    ]);
}



        /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        if (Auth::user()) {
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
    }
    
}
