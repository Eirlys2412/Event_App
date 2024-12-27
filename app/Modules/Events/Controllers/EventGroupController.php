<?php

namespace App\Modules\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Modules\Events\Models\Event; // Đảm bảo dùng đúng model Event
use App\Modules\Group\Models\Group;

class EventGroupController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    public function index()
{
    // Kiểm tra quyền truy cập
    if (!$this->check_function("event_group_list")) {
        return redirect()->route('unauthorized');
    }

    // Lấy danh sách Event Group và phân trang, eager load dữ liệu từ bảng Event
    $eventGroups = EventGroup::with('event') // Eager load mối quan hệ 'event'
        ->orderBy('id', 'DESC')
        ->paginate($this->pagesize);

    // Định nghĩa biến active_menu
    $active_menu = "event_group_list";
    return view('Events::event_group.index', compact('eventGroups', 'active_menu'));
}

    public function create()
    {
        // Kiểm tra quyền truy cập
        if (!$this->check_function("event_group_add")) {
            return redirect()->route('unauthorized')->with('error', 'Bạn không có quyền truy cập vào chức năng này.');
        }

        // Lấy danh sách các nhóm (groups)
        $groups = Group::all(); // Giả sử bạn có một model Group để lấy danh sách nhóm

        // Lấy tất cả các sự kiện
        $events = Event::all();  // Lấy tất cả các sự kiện từ bảng Event

        // Định nghĩa biến active_menu
        $active_menu = "event_group_add";
        return view('Events::event_group.create', compact('groups', 'events', 'active_menu'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id', 
            'event_id' => 'required|exists:event,id',  // Đảm bảo bảng 'event' và 'event_id' đúng
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Vui lòng kiểm tra lại các trường đã nhập!')
                ->withInput();
        }

        EventGroup::create([
            'group_id' => $request->input('group_id'),
            'event_id' => $request->input('event_id'),
        ]);

        return redirect()->route('admin.event_group.index')->with('success', 'Event Group đã được thêm thành công!');
    }

    public function edit($id)
{
    if (!$this->check_function("event_group_edit")) {
        return redirect()->route('unauthorized');
    }

    $eventGroup = EventGroup::findOrFail($id);
    $groups = Group::all();  // Lấy danh sách các nhóm
    $events = Event::all();  // Lấy danh sách các sự kiện

    $active_menu = 'event_group';
    return view('Events::event_group.edit', compact('eventGroup', 'groups', 'events', 'active_menu'));
}


    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id', // Xác thực trường group_id
            'event_id' => 'required|exists:event,id', // Xác thực trường event_id
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Lấy Event Group cần cập nhật
        $eventGroup = EventGroup::findOrFail($id);

        // Cập nhật thông tin Event Group
        $eventGroup->group_id = $request->input('group_id');
        $eventGroup->event_id = $request->input('event_id');

        // Lưu vào cơ sở dữ liệu
        $eventGroup->save();

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.event_group.index')->with('success', 'Event Group đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        // Lấy Event Group cần xóa
        $eventGroup = EventGroup::findOrFail($id);
        $eventGroup->delete();

        return redirect()->route('admin.event_group.index')->with('success', 'Event Group đã được xóa thành công.');
    }
}
