<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Events\Models\EventAttendance;
use App\Modules\Events\Models\EventUser;
use App\Modules\Events\Models\Event;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventAttendanceApiController extends Controller
{
    // Lấy danh sách điểm danh (pagination optional)
    public function index(Request $request)
    {
        $attendances = EventAttendance::with(['user', 'event'])
                        ->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => true,
            'data' => $attendances
        ]);
    }

    // Thêm người dùng điểm danh (check-in)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:event,id',
            'location' => 'nullable|string'
        ]);

        // Kiểm tra nếu người dùng đã tham gia sự kiện
        $already = EventAttendance::where('user_id', $request->user_id)
                                  ->where('event_id', $request->event_id)
                                  ->first();

        if ($already && $already->checked_in_at) {
            return response()->json([
                'status' => false,
                'message' => 'Người dùng đã điểm danh sự kiện này.'
            ], 409);
        }

        $attendance = $already ?? new EventAttendance();
        $attendance->user_id = $request->user_id;
        $attendance->event_id = $request->event_id;
        $attendance->location = $request->location;
        $attendance->checked_in_at = Carbon::now();
        $attendance->save();

        return response()->json([
            'status' => true,
            'message' => 'Điểm danh thành công!',
            'data' => $attendance
        ]);
    }

    // Xem chi tiết điểm danh
    public function show($id)
    {
        $attendance = EventAttendance::with(['user', 'event'])->find($id);

        if (!$attendance) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $attendance
        ]);
    }

    // Cập nhật thông tin điểm danh
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:event,id'
        ]);

        $attendance = EventAttendance::findOrFail($id);
        $attendance->update($request->only(['user_id', 'event_id', 'location']));

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công!',
            'data' => $attendance
        ]);
    }

    // Xóa điểm danh
    public function destroy($id)
    {
        $attendance = EventAttendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa thành công!'
        ]);
    }

    // Điểm danh bằng QR code
    public function checkInByQr(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:event,id',
                'qr_token' => 'required|string'
            ]);

            $userId = Auth::id();

            // Kiểm tra sự kiện tồn tại
            $event = Event::find($request->event_id);
            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sự kiện không tồn tại.'
                ], 404);
            }

            // Kiểm tra người dùng có tham gia sự kiện không
            $eventUser = EventUser::where('event_id', $request->event_id)
                                ->where('user_id', $userId)
                                ->first();
            if (!$eventUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn chưa đăng ký tham gia sự kiện này.'
                ], 403);
            }

            // Kiểm tra đã điểm danh chưa
            $existing = EventAttendance::where('event_id', $request->event_id)
                                      ->where('user_id', $userId)
                                      ->first();
            if ($existing && $existing->checked_in_at) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn đã điểm danh sự kiện này trước đó.',
                    'checked_in_at' => $existing->checked_in_at
                ], 409);
            }

            // Tạo điểm danh mới
            $checkIn = new EventAttendance();
            $checkIn->event_id = $request->event_id;
            $checkIn->user_id = $userId;
            $checkIn->checked_in_at = Carbon::now();
            $checkIn->qr_token = $request->qr_token;
            $checkIn->save();

            return response()->json([
                'status' => true,
                'message' => 'Điểm danh thành công!',
                'data' => [
                    'event_name' => $event->title,
                    'checked_in_at' => $checkIn->checked_in_at,
                    'user_id' => $checkIn->user_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã có lỗi xảy ra khi điểm danh: ' . $e->getMessage()
            ], 500);
        }
    }
}