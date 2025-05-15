<?php

namespace App\Modules\Events\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventAttendance;
use App\Modules\Events\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Modules\Events\Models\EventUser;
use Illuminate\Support\Facades\Auth;

class EventAttendanceController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    // Danh sách tham gia sự kiện
    public function index()
    {
        $attendances = EventAttendance::with(['user', 'event'])->paginate($this->pagesize);
        $events = Event::all(); 
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách tham gia sự kiện</li>';
        $active_menu = "event_attendance_list";

        return view('Events::event_attendance.index', compact('attendances', 'breadcrumb', 'active_menu', 'events'));
    }

        // Hiển thị form tạo mới tham gia sự kiện
public function create()
{
    $events = Event::all();
    $users = User::all();
    $attendances = EventAttendance::with(['user', 'event'])->paginate($this->pagesize);

    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm tham gia sự kiện</li>';
    $active_menu = "event_attendance_create";

    return view('Events::event_attendance.create', compact('events', 'users', 'breadcrumb', 'active_menu', 'attendances'));
}

    // Thêm người tham gia vào sự kiện
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
            'event_id' => 'required|exists:event,id'
        ]);

        if (empty($request->user_id)) {
            return redirect()->back()->withErrors(['user_id' => 'Không có người dùng nào được chọn.']);
        }

        DB::transaction(function () use ($request) {
           foreach ($request->user_id as $user_ids) {
                EventAttendance::firstOrCreate([
                    'user_id' => $user_ids,
                    'event_id' => $request->event_id
                ]);
            }
     });

        return redirect()->route('admin.event_attendance.index')->with('success', 'Người dùng đã được thêm vào sự kiện thành công!');
    }

    public function generateQrCode($eventId)
    {
        try {
            $event = Event::findOrFail($eventId);
            
            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(16));
            
            // Thời gian hết hạn (ví dụ: 5 phút)
            $expiresAt = now()->addMinutes(50);
            
            // Dữ liệu QR bao gồm event_id, token và thời gian hết hạn
            $qrData = json_encode([
                'event_id' => $eventId,
                'qr_token' => $token,
                'expires_at' => $expiresAt->timestamp
            ]);
            
            // Lưu token vào cache để xác thực sau này
            cache()->put("event_qr_{$eventId}", $token, $expiresAt);
            
            // Tạo mã QR
            $qrCode = QrCode::size(300)->generate($qrData);
            
            $active_menu = 'event_attendance_qr';
            
            return view('Events::event_attendance.qr', compact('qrCode', 'qrData', 'active_menu', 'expiresAt'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể tạo mã QR: ' . $e->getMessage());
        }
    }

    public function checkIn($eventId, Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng đăng nhập để điểm danh.'
                ], 401);
            }

            $userId = Auth::id();

            // Kiểm tra sự kiện tồn tại
            $event = Event::findOrFail($eventId);

            // Kiểm tra người dùng đã đăng ký sự kiện
            $eventUser = EventUser::where('event_id', $eventId)
                                ->where('user_id', $userId)
                                ->first();

            if (!$eventUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn chưa đăng ký tham gia sự kiện này.'
                ], 403);
            }

            // Kiểm tra token QR
            $storedToken = cache()->get("event_qr_{$eventId}");
            if (!$storedToken || $storedToken !== $request->qr_token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã QR không hợp lệ hoặc đã hết hạn.'
                ], 403);
            }

            // Kiểm tra đã điểm danh chưa
            $existingAttendance = EventAttendance::where('event_id', $eventId)
                                               ->where('user_id', $userId)
                                               ->first();

            if ($existingAttendance && $existingAttendance->checked_in_at) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn đã điểm danh sự kiện này trước đó.',
                    'checked_in_at' => $existingAttendance->checked_in_at
                ], 409);
            }

            // Tạo điểm danh mới
            $attendance = EventAttendance::create([
                'event_id' => $eventId,
                'user_id' => Auth::id(),
                'checked_in_at' => now(),
                'qr_token' => $request->qr_token
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Điểm danh thành công!',
                'data' => [
                    'user' => $attendance->user->full_name,
                    'event' => $attendance->event->title,
                    'check_in_time' => $attendance->checked_in_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Chỉnh sửa thông tin tham gia sự kiện
    public function edit($id)
    {
        $attendance = EventAttendance::findOrFail($id);
        $events = Event::all();
        $users = User::all();

        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa tham gia sự kiện</li>';
        $active_menu = "event_attendance_edit";

        return view('Events::event_attendance.edit', compact('attendance', 'events', 'users', 'breadcrumb', 'active_menu'));
    }

    // Cập nhật thông tin tham gia sự kiện
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:event,id'
        ]);

        $attendance = EventAttendance::findOrFail($id);
        $attendance->update($request->only(['user_id', 'event_id']));

        return redirect()->route('admin.event_attendance.index')->with('success', 'Thông tin tham gia sự kiện đã được cập nhật!');
    }

    // Xóa người tham gia khỏi sự kiện
    public function destroy($id)
    {
        $attendance = EventAttendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.event_attendance.index')->with('success', 'Người tham gia đã được xóa khỏi sự kiện thành công!');
    }

    public function updateStatus(Request $request)
    {
        try {
            $eventAttendance = EventAttendance::findOrFail($request->id);

            // Cập nhật trạng thái
            $eventAttendance->status = $request->mode ? 'active' : 'inactive';
            $eventAttendance->save();

            // Trả về phản hồi thành công
            return response()->json([
                'status' => true,
                'msg' => 'Cập nhật trạng thái thành công!',
            ]);
        } catch (\Exception $e) {
            // Trả về phản hồi lỗi
            return response()->json([
                'status' => false,
                'msg' => 'Không thể thay đổi trạng thái. Vui lòng thử lại!: ' . $e->getMessage(),
            ]);
        }
    }

    public function showUsers($eventId)
    {
        $event = Event::findOrFail($eventId);
        $users = EventUser::with('user')->where('event_id', $eventId)->get();

        return view('Events::event_user.index', compact('event', 'users', 'events'));
    }

    public function addUser(Request $request, $eventId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        EventUser::create([
            'event_id' => $eventId,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('events.users', $eventId)->with('success', 'User added successfully.');
    }

    public function editUser(Request $request, $eventId, $userId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $eventUser = EventUser::where('event_id', $eventId)->where('user_id', $userId)->firstOrFail();
        $eventUser->update(['user_id' => $request->user_id]);

        return redirect()->route('events.users', $eventId)->with('success', 'User updated successfully.');
    }

    public function deleteUser($eventId, $userId)
    {
        $eventUser = EventUser::where('event_id', $eventId)->where('user_id', $userId)->firstOrFail();
        $eventUser->delete();

        return redirect()->route('events.users', $eventId)->with('success', 'User deleted successfully.');
    }
}
