<?php

namespace App\Modules\Events\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventAttendance;
use App\Modules\Events\Models\EventUser;
use App\Modules\Events\Models\Event;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Modules\Events\Models\EventRegistration;
use Maatwebsite\Excel\Facades\Excel;
class EventUserController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    $query = EventUser::with(['user', 'event', 'role']);

    if ($request->filled('event_id')) {
        $query->where('event_id', $request->event_id);
    }

    $eventUsers = $query->paginate($this->pagesize)->appends($request->all()); // Giữ lại query khi phân trang
    $events = Event::all(); // Dùng cho combobox

    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng tham gia sự kiện</li>';
    $active_menu = "event_user_list";

    return view('Events::event_user.index', compact('eventUsers', 'events', 'breadcrumb', 'active_menu'));
}

    


    public function create()
    {
        $events = Event::all();
        $roles = Role::all();
        $users = User::where('status', 'active')->get(); // Lấy tất cả người dùng hệ thống đang hoạt động

        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.event_user.index') . '">Người dùng sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>';
        $active_menu = "event_user_create";
    
        return view('Events::event_user.create', compact('events', 'roles', 'users', 'breadcrumb', 'active_menu'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:event,id',
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Kiểm tra xem người dùng đã được thêm vào sự kiện chưa
        $existingUser = EventUser::where('event_id', $request->event_id)
                                ->where('user_id', $request->user_id)
                                ->exists();

        if ($existingUser) {
            return redirect()->back()->withErrors(['user_id' => 'Người dùng đã được thêm vào sự kiện này.']);
        }

        // Thêm người dùng vào sự kiện
        EventUser::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
        ]);

        // Tự động thêm vào danh sách điểm danh
      
    
        return redirect()->route('admin.event_user.index')->with('success', 'Người dùng đã được thêm vào sự kiện thành công!');
    }
    
    public function edit($id)
    {
        $eventUser = EventUser::findOrFail($id);
        $users = User::where('status', 'active')->get(); // Lấy tất cả người dùng hệ thống đang hoạt động
        $events = Event::all();
        $roles = Role::all();

        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.event_user.index') . '">Người dùng sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>';
        $active_menu = "event_user_edit";
    
        return view('Events::event_user.edit', compact('eventUser', 'users', 'events', 'roles', 'breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:event,id',
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $eventUser = EventUser::findOrFail($id);
        $eventUser->update($request->all());

        return redirect()->route('admin.event_user.index')->with('success', 'Thông tin người dùng đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $eventUser = EventUser::findOrFail($id);
        $eventUser->delete();

        return redirect()->route('admin.event_user.index')->with('success', 'Người dùng đã được xóa khỏi sự kiện thành công!');
    }

    public function joinEvent(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if the user is already participating in the event
        $existingParticipation = EventUser::where('event_id', $request->event_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingParticipation) {
            return response()->json([
                'status' => false,
                'message' => 'User is already participating in this event.'
            ], 400);
        }

        // Add user to the event
        EventUser::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'role_id' => 1, // Assuming '1' is the role ID for 'member'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User has successfully joined the event.'
        ]);
    }

    public function listParticipants($eventId)
    {
        $participants = EventUser::with('user')
            ->where('event_id', $eventId)
            ->get();

        return view('Events::event_user.participants', compact('participants'));
    }
    public function export($eventId)
{
    $event = Event::findOrFail($eventId);
    $filename = 'danh_sach_nguoi_dung_' . \Illuminate\Support\Str::slug($event->title) . '.xlsx';

    return Excel::download(new \App\Exports\EventUsersExport($eventId), $filename);
}
public function viewProfile($id)
{
    $user = User::with([
        'blogs' => function ($query) {
            $query->latest()->take(3);
        },
        'eventUsers.event',
    ])->findOrFail($id);

    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.event_user.index') . '">Người dùng sự kiện</a></li>
        <li class="breadcrumb-item active" aria-current="page">Hồ sơ người dùng</li>';
    $active_menu = "event_user_profile";

    return view('Events::event_user.profile', compact('user', 'breadcrumb', 'active_menu'));
}

}