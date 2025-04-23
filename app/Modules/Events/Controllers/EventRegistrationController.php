<?php

namespace App\Modules\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventRegistration;
use App\Modules\Events\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Modules\Events\Models\EventUser;
use App\Models\Role;

class EventRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()

    {
        $active_menu = 'event_registration_list';
        $events = Event::all();
        $users = User::all();
        $registrations = EventRegistration::with(['user', 'event'])->paginate(10);
        return view('Events::event_registration.index', compact('registrations', 'active_menu', 'events', 'users'));
    }

    public function create()
    {
        $active_menu = 'event_registration_add';
        $events = Event::all();
        $users = User::all();
        return view('Events::event_registration.create', compact('events', 'users', 'active_menu'));
    }

    public function store(Request $request)
{
    $active_menu = 'event_registration_add';    
    $request->validate([
        'event_id' => 'required|exists:event,id',
        'user_id' => 'required|exists:users,id',
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $registration = EventRegistration::create($request->all());

    // Nếu trạng thái là 'approved', thêm vào event_user
    if ($request->status === 'approved') {
        EventUser::firstOrCreate([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id
        ], [
            'role' => 'participant' // hoặc 'member', tùy bạn setup
        ]);
    }

    return redirect()->route('admin.event_registration.index')
        ->with('success', 'Đăng ký sự kiện đã được tạo thành công.');
}


    public function edit($id)
    {
        $active_menu = 'event_registration_edit';
        $registration = EventRegistration::findOrFail($id);
        $events = Event::all();
        $users = User::all();
        $roles = Role::all();
        return view('Events::event_registration.edit', compact('registration', 'events', 'users', 'active_menu', 'roles'));
    }

    public function update(Request $request, $id)
{
    $active_menu = 'event_registration_edit';
    $request->validate([
        'event_id' => 'required|exists:event,id',
        'user_id' => 'required|exists:users,id',
        'role_id' => 'required_if:status,approved|integer',
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $registration = EventRegistration::findOrFail($id);
    $registration->update($request->all());

    // Nếu trạng thái mới là 'approved', thêm vào event_user nếu chưa có
    if ($request->status === 'approved') {
        EventUser::firstOrCreate([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
        ], );
    }

    return redirect()->route('admin.event_registration.index')
        ->with('success', 'Đăng ký sự kiện đã được cập nhật thành công.');
}


    public function destroy($id)
    {
        $active_menu = 'event_registration_list';
        $registration = EventRegistration::findOrFail($id);
        $registration->delete();

        return redirect()->route('admin.event_registration.index')
            ->with('success', 'Đăng ký sự kiện đã được xóa thành công.');
    }
} 