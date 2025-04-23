<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventRegistration;
use App\Modules\Events\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventRegistrationApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Đăng ký tham gia sự kiện
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:event,id',
            'reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra xem đã đăng ký chưa
        $existingRegistration = EventRegistration::where('event_id', $request->event_id)
                                               ->where('user_id', Auth::id())
                                               ->exists();

        if ($existingRegistration) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã đăng ký tham gia sự kiện này rồi.'
            ], 400);
        }

        // Tạo đăng ký mới
        $registration = EventRegistration::create([
            'event_id' => $request->event_id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'reason' => $request->reason
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký tham gia sự kiện thành công!',
            'data' => $registration
        ]);
    }

    // Cập nhật đăng ký
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $registration = EventRegistration::findOrFail($id);

        // Kiểm tra xem người dùng có quyền cập nhật đăng ký này không
        if ($registration->user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền cập nhật đăng ký này.'
            ], 403);
        }

        $registration->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật đăng ký thành công!',
            'data' => $registration
        ]);
    }

    // Xóa đăng ký
    public function destroy($id)
    {
        $registration = EventRegistration::findOrFail($id);

        // Kiểm tra xem người dùng có quyền xóa đăng ký này không
        if ($registration->user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền xóa đăng ký này.'
            ], 403);
        }

        $registration->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký sự kiện đã được xóa thành công!'
        ]);
    }

    // Xem tất cả đăng ký của người dùng
    public function myRegistrations()
    {
        $registrations = EventRegistration::with(['event', 'user'])
                                        ->where('user_id', Auth::id())
                                        ->get();

        return response()->json([
            'status' => true,
            'data' => $registrations
        ]);
    }

    // Xem chi tiết đăng ký
    public function show($id)
    {
        $registration = EventRegistration::with(['event', 'user'])
                                       ->where('user_id', Auth::id())
                                       ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $registration
        ]);
    }
}
