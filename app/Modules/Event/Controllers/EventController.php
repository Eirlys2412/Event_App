<?php

namespace App\Modules\Event\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Event\Models\Event;

class EventController extends Controller
{
    // Pagination settings
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    // List all events
    public function index()
    {
        $func = "event_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "event_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách sự kiện</li>';
        
        $events = Event::orderBy('id', 'DESC')->paginate($this->pagesize);
        
        return view('Event::event.index', compact('events', 'breadcrumb', 'active_menu'));
    }

    // Show form to create a new event
    public function create()
    {
        $func = "event_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $data['active_menu'] = "event_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.eventtype.index') . '">Sự kiện</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo sự kiện</li>';

        return view('Event::event.create', $data);
    }

    // Store a new event
    public function store(Request $request)
    {
        $func = "event_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $this->validate($request, [
            'title' => 'string|required',
            'description' => 'string|required',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        
        // Handle slug creation
        $slug = Str::slug($request->input('title'));
        $slug_count = Event::where('slug', $slug)->count();
        if ($slug_count > 0) {
            $slug .= time() . '-' . $slug;
        }
        $data['slug'] = $slug;
        $data['status'] = ($request->input('status') == 'active') ? 1 : 0;
        $data['user_id'] = auth()->user()->id;

        $event = Event::create($data);

        if ($event) {
            return redirect()->route('admin.eventtype.index')->with('success', 'Tạo sự kiện thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    // Show form to edit an existing event
    public function edit(string $id)
    {
        $func = "event_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if ($event) {
            $active_menu = "event_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.eventtype.index') . '">Sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa sự kiện</li>';

            return view('Event::event.edit', compact('breadcrumb', 'event', 'active_menu'));
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Update an existing event
    public function update(Request $request, string $id)
    {
        $func = "event_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if ($event) {
            $this->validate($request, [
                'title' => 'string|required',
                'description' => 'string|required',
                'status' => 'required|in:active,inactive',
            ]);

            $data = $request->all();
            $data['status'] = ($request->input('status') == 'active') ? 1 : 0;

            $status = $event->fill($data)->save();

            if ($status) {
                return redirect()->route('admin.eventtype.index')->with('success', 'Cập nhật sự kiện thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Delete an event
    public function destroy(string $id)
    {
        $func = "event_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $event = Event::find($id);
        if ($event) {
            $status = $event->delete();
            if ($status) {
                return redirect()->route('admin.eventtype.index')->with('success', 'Xóa sự kiện thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Toggle status of an event
    public function eventStatus(Request $request)
    {
        $func = "event_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $status = $request->mode == 'true' ? 1 : 0;
        DB::table('eventtype')->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['msg' => "Cập nhật thành công", 'status' => true]);
    }
}
