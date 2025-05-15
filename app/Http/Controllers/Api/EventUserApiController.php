<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Events\Models\EventUser;
use App\Modules\Events\Models\Event;
use App\Models\User;
use App\Models\Role;

class EventUserApiController extends Controller
{
    public function index(Request $request)
{
    try {
        $query = EventUser::with(['user', 'event', 'role']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $eventUsers = $query->paginate(20); 

        return response()->json([
            'status' => true,
            'data' => $eventUsers
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        ], 500);
    }
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $exists = EventUser::where('event_id', $validated['event_id'])
                            ->where('user_id', $validated['user_id'])
                            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Người dùng đã tham gia sự kiện này.'
            ], 409);
        }

        $eventUser = EventUser::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Thêm người dùng vào sự kiện thành công!',
            'data' => $eventUser
        ], 201);
    }

    public function show($user_id)
    {
        $eventUser = EventUser::with(['user', 'event', 'role'])->find($user_id);

        if (!$eventUser) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy bản ghi.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $eventUser
        ]);
    }

    public function update(Request $request, $id)
    {
        $eventUser = EventUser::findOrFail($id);

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $eventUser->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công!',
            'data' => $eventUser
        ]);
    }

    public function destroy($id)
    {
        $eventUser = EventUser::find($id);

        if (!$eventUser) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy bản ghi.'
            ], 404);
        }

        $eventUser->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa thành công.'
        ]);
    }

    public function joinEvent(Request $request)
{
    try {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Kiểm tra nếu người dùng có vai trò phù hợp
        $user = User::find($validated['user_id']);
        if ($user->role !== 'participant') { // Kiểm tra vai trò là 'participant'
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền tham gia sự kiện này'
            ], 403); // Forbidden
        }

        $exists = EventUser::where('event_id', $validated['event_id'])
                           ->where('user_id', $validated['user_id'])
                           ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Người dùng đã tham gia sự kiện.'
            ], 409);
        }

        $eventUser = EventUser::create([
            'event_id' => $validated['event_id'],
            'user_id' => $validated['user_id'],
            'role_id' => $validated['role_id'] // default role (e.g. thành viên)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tham gia sự kiện thành công!',
            'data' => $eventUser
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        ], 500);
    }
}


    public function listParticipants($eventId)
    {
        $participants = EventUser::with('user')
            ->where('event_id', $eventId)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $participants
        ]);
    }
    public function getUserEvents($userId)
    {
        // Lấy các bản ghi EventUser có user_id tương ứng và load cả event
        $eventUsers = EventUser::with('event')
            ->where('user_id', $userId)
            ->get();
    
        $events = $eventUsers->map(function ($eu) {
            return [
                'id' => $eu->event->id,
                'title' => $eu->event->title,
                'description' => $eu->event->description,
                'timestart' => $eu->event->timestart,
                'timeend' => $eu->event->timeend,
                'status' => 'approved', // hoặc lấy từ EventUser nếu có
            ];
        });
    
        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }
    

}
