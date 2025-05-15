<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Community\Models\CommunityGroup;
use App\Modules\Community\Models\JoinRequest;
use App\Models\User; // Import đúng namespace của User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JoinRequestNotification;
use Illuminate\Support\Facades\Log; // Thêm import này
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CommunityController extends Controller
{
    public function index()
    {
        $groups = CommunityGroup::all();
        return response()->json($groups);
    }

    public function uploadCover(Request $request)
{
    try {
        if (!auth()->check()) {
            Log::warning('No authenticated user for uploading cover', [
                'token' => $request->bearerToken(),
            ]);
            return response()->json(['message' => 'Bạn cần đăng nhập'], 401);
        }

        // Kiểm tra token và file trong log
        Log::info('Upload cover attempt', [
            'user_id' => auth()->id(),
            'token' => $request->bearerToken(),
            'file' => $request->hasFile('cover_image') ? $request->file('cover_image')->getClientOriginalName() : 'No file',
        ]);

        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('cover_image');
        $filename = time() . '-' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.jpg';
        $path = $image->storeAs('uploads/community/covers', $filename, 'public');

        // Tạm bỏ convertToJpg để kiểm tra
        // $imagePath = storage_path('app/public/' . $path);
        // $this->convertToJpg($imagePath);

        Log::info('Cover image uploaded successfully', [
            'user_id' => auth()->id(),
            'path' => $path,
        ]);

        return response()->json(['url' => $path], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed for cover upload', [
            'errors' => $e->errors(),
            'request' => $request->all(),
        ]);
        return response()->json(['message' => 'Dữ liệu không hợp lệ: ' . $e->getMessage()], 422);
    } catch (\Exception $e) {
        Log::error('Failed to upload cover image', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);
        return response()->json(['message' => 'Lỗi khi tải ảnh: ' . $e->getMessage()], 500);
    }
}
public function store(Request $request)
{
    try {
        if (!auth()->check()) {
            Log::warning('No authenticated user for creating group', [
                'token' => $request->bearerToken(),
            ]);
            return response()->json(['message' => 'Bạn cần đăng nhập để tạo nhóm'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string', // Đảm bảo nhận cover_image
            'privacy' => 'required|in:public,private,hidden',
            'status' => 'required|in:active,inactive',
        ]);

        Log::info('Creating group with data', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        $group = CommunityGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $request->cover_image, // Đảm bảo gán cover_image
            'privacy' => $request->privacy,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'slug' => Str::slug($request->name),
        ]);

        Log::info('Group created successfully', [
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'slug' => $group->slug,
            'cover_image' => $group->cover_image, // Log để kiểm tra
        ]);

        return response()->json($group, 201);
    } catch (\Exception $e) {
        Log::error('Failed to create group', [
            'error' => $e->getMessage(),
            'request' => $request->all(),
            'token' => $request->bearerToken(),
        ]);
        return response()->json(['message' => 'Lỗi khi tạo nhóm: ' . $e->getMessage()], 500);
    }
}

    // Hàm chuyển đổi ảnh thành jpg
    private function convertToJpg($imagePath)
    {
        $image = imagecreatefromstring(file_get_contents($imagePath));
        imagejpeg($image, $imagePath, 90); // Chuyển thành jpg với chất lượng 90
        imagedestroy($image);
    }

    public function show($id)
    {
        $group = CommunityGroup::findOrFail($id);
        $posts = $group->posts;
        return response()->json($group);
    }

    public function requestToJoin(Request $request, $groupId)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để gửi yêu cầu tham gia'], 401);
        }

        $group = CommunityGroup::findOrFail($groupId);

        $existingRequest = JoinRequest::where('group_id', $groupId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'Bạn đã gửi yêu cầu hoặc đã tham gia nhóm này'], 400);
        }

        $joinRequest = JoinRequest::create([
            'group_id' => $groupId,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        $creator = $group->created_by ? User::find($group->created_by) : null;
        if ($creator) {
            Notification::send($creator, new JoinRequestNotification($joinRequest));
        }

        return response()->json(['message' => 'Yêu cầu tham gia đã được gửi'], 200);
    }

    public function cancelJoinRequest(Request $request, $groupId)
    {
        $joinRequest = JoinRequest::where('group_id', $groupId)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if (!$joinRequest) {
            return response()->json(['message' => 'Không tìm thấy yêu cầu tham gia'], 404);
        }

        $joinRequest->delete();

        return response()->json(['message' => 'Đã hủy yêu cầu tham gia'], 200);
    }

    public function getJoinRequests($groupId)
    {
        $group = CommunityGroup::findOrFail($groupId);

        if ($group->created_by !== auth()->id()) {
            return response()->json(['message' => 'Bạn không có quyền xem yêu cầu này'], 403);
        }

        $requests = JoinRequest::where('group_id', $groupId)
            ->with('user')
            ->where('status', 'pending')
            ->get();

        return response()->json($requests);
    }

    public function manageJoinRequest(Request $request, $groupId, $requestId)
    {
        $group = CommunityGroup::findOrFail($groupId);
        Log::info('Manage join request attempt', [
            'user_id' => auth()->id(),
            'group_creator_id' => $group->created_by,
            'group_id' => $groupId,
            'request_id' => $requestId,
            'token' => $request->bearerToken(),
        ]);

        if ($group->created_by !== auth()->id()) {
            Log::warning('Permission denied', [
                'user_id' => auth()->id(),
                'group_creator_id' => $group->created_by,
            ]);
            return response()->json(['message' => 'Bạn không có quyền quản lý yêu cầu này'], 403);
        }

        $joinRequest = JoinRequest::findOrFail($requestId);
        if ($joinRequest->group_id != $groupId) {
            return response()->json(['message' => 'Yêu cầu không thuộc nhóm này'], 400);
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $joinRequest->status = 'approved';
            // Thêm người dùng vào bảng community_members
            $group->members()->attach($joinRequest->user_id, [
                'role' => 'member', // Vai trò mặc định
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $joinRequest->status = 'rejected';
        }

        $joinRequest->save();

        return response()->json([
            'message' => "Yêu cầu đã được " . ($request->action === 'approve' ? 'duyệt' : 'từ chối'),
            'join_request' => $joinRequest,
        ]);
    }

    public function getJoinRequestStatus()
{
    if (!auth()->check()) {
        return response()->json(['message' => 'Bạn cần đăng nhập để xem trạng thái'], 401);
    }

    $statuses = JoinRequest::where('user_id', auth()->id())
        ->pluck('status', 'group_id')
        ->toArray();

    return response()->json($statuses);
}
}