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
        'phone' => 'string|nullable',
        'email' => 'string|required|email',
        'password' => 'string|required',
        'role' => 'string|required|in:eventmanager,eventmember',
    ]);

    $data = $request->all();

    // // Kiểm tra số điện thoại đã tồn tại
    if (\App\Models\User::where('phone', $data['phone'])->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Số điện thoại đã tồn tại',
        ], 200);
    }

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
    $data['phone'] = $data['phone'] ?? null; // Cho phép phone NULL

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
        $user = User::with(['eventmanager', 'eventregistration'])->find(Auth::user()->id);

        if ($user->status == 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        } else {
            $user_token['token'] = $user->createToken('appToken')->accessToken;

            // Get student_id and teacher_id if they exist

            $eventmanagerId = $user->eventmanager ? $user->eventmanager->id : 0;
            $eventregistrationId = $user->eventregistration ? $user->eventregistration->id : 0;
            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
                'eventmanager_id' => $eventmanagerId,
                'eventregistration_id' => $eventregistrationId, // Return teacher_id
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




// public function createEventmember(Request $request)
// {
//     $this->validate($request, [
        
//         'user_id' => 'integer|required|exists:users,id',
        
//     ]);

//     try {
//         $data = $request->all();

//         // Tạo slug từ MSSV
//         $data['slug'] = Str::slug($data['user_id'], '-');

//         // Thêm dữ liệu vào bảng students
//         $eventmember = \App\Modules\Events\Models\EventRegistration::create([
//             'user_id' => $data['user_id'],
//             'event_id' => $data['event_id'],
//             'status' => 'pending',
//             'reason' => $data['reason'],
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Eventmember created successfully',
//             'data' => $eventmember,
//         ], 201); // Trả về mã trạng thái 201 khi tạo thành công
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to create eventmember',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }

// public function createEventmanager(Request $request)
// {
//     $this->validate($request, [
//         'user_id' => 'integer|required|exists:users,id',
//         'slug' => 'string|required|unique:event_managers,slug',
//     ]);

//     try {
//         $data = $request->all();

//         // Tạo slug từ mã giảng viên
//         $data['slug'] = Str::slug($data['user_id'], '-');

//         // Thêm dữ liệu vào bảng teachers
//         $eventmanager = \App\Modules\Events\Models\EventManager::create([
//             'user_id' => $data['user_id'],
//             'slug' => $data['slug'],
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Eventmanager created successfully',
//             'data' => $eventmanager,
//         ], 201);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to create eventmanager',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }

public function googleSignIn(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'full_name' => 'required|string',
        'google_id' => 'required|string',
        'role' => 'nullable|string|in:eventmanager,eventmember',
        'budget' => 'nullable|numeric',
    ]);

    // Kiểm tra nếu user đã tồn tại hoặc tạo mới
    $user = User::firstOrCreate(
        ['email' => $request->email],
        [
            'full_name' => $request->full_name,
            'google_id' => $request->google_id,
            'username' => $request->input('username', null),
            'role' => $request->input('role', 'eventmember'),
            'budget' => $request->input('budget', 0),
            'phone' => $request->input('phone', null),
            'password' => bcrypt('default_password'),
            'status' => 'active',
            'photo' => 'backend/images/profile-6.jpg',
            'remember_token' => Str::random(60),
        ]
    );

    // Lấy thông tin student_id và teacher_id nếu có
    $user->load(['eventmanager', 'eventmember']); // Eager load để tránh query thừa

    $eventmanagerId = $user->eventmanager ? $user->eventmanager->id : 0;
    $eventmemberId = $user->eventmember ? $user->eventmember->id : 0;

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
        'eventmanager_id' => $eventmanagerId,
        'eventmember_id' => $eventmemberId,
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
