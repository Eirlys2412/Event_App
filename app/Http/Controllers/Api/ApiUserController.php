<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AuthenticationController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Models\Role;

class ApiUserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function viewProfile(Request $request)
{
    $user = $request->user();
    return response()->json([
        'profile' => $user
    ]);
}

    public function updateProfile(Request $request)
{
    $this->validate($request, [
        'full_name' => 'string|required',
        'address' => 'string|required',
        'photo' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    $user = $request->user();
    $data = $request->all();

    // Kiểm tra sự tồn tại của 'photo'
    $photo = $data['photo'] ?? null;
    $photoOld = $data['photo_old'] ?? $user->photo;

    // Logic cập nhật photo
    if ($photo === null || $photo === "") {
        $photo = $photoOld;
    }
    if ($photo === null || $photo === "") {
        $photo = asset('backend/images/profile-6.jpg');
    }

    $data['photo'] = $photo;

    // Lưu thông tin user
    $status = $user->fill($data)->save();

    if ($status) {
        return response()->json(['message' => 'Bạn đã cập nhật thành công'], 200);
    } else {
        return response()->json(['message' => 'Lỗi xảy ra'], 400);
        }
    }

    public function uploadPhoto(Request $request)
{
    $this->validate($request, [
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    try {
        $user = $request->user();
        
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Upload file vào thư mục storage/app/public/photos
            $path = $file->storeAs('study_app', $fileName, 'public');
            
            // Thêm tiền tố "storage/" vào đường dẫn khi lưu vào database
            $storagePath = 'storage/' . $path;
            $user->updatePhoto($storagePath);

            // Trả về URL đầy đủ trong response để hiển thị
            return response()->json([
                'message' => 'Upload ảnh thành công',
                'photo_url' => asset($storagePath)
            ], 200);
        }

        return response()->json(['message' => 'Không tìm thấy file ảnh'], 400);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi upload ảnh: ' . $e->getMessage()], 500);
    }
}
public function show($id)
{
    // Tải trước mối quan hệ 'role' và 'blogs' (điều chỉnh 'blogs' nếu tên quan hệ khác)
    $user = User::with(['role', 'blogs'])->findOrFail($id);

    // Tính điểm trung bình nếu relationship 'votes' tồn tại và có dữ liệu
    $voteAverage = null;
    // Giả sử relationship là 'votes' và cột điểm là 'rating' hoặc 'score'
    // Kiểm tra xem user có relationship 'votes' và có vote nào không trước khi tính avg
    if (method_exists($user, 'votes') && $user->votes()->exists()) {
         // Điều chỉnh 'rating' hoặc 'score' tùy thuộc vào cột điểm trong bảng vote
        $voteAverage = round($user->votes()->avg('rating'), 2); 
        // Hoặc nếu cột là 'score': $voteAverage = round($user->votes()->avg('score'), 2);
    }

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'photo' => asset($user->photo ?? 'backend/images/profile-6.jpg'),
            'description' => $user->description,
            'role' => $user->role,
            'phone' => $user->phone,
            'address' => $user->address,
            'budget' => $user->budget,
            'blogs' => $user->blogs, // Thêm danh sách bài viết của user
            'vote_average' => $voteAverage, // Thêm điểm trung bình (nếu tính được)
        ]
    ]);
}

}
