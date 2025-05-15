<?php

namespace App\Modules\Events\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Events\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventTypeController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('admin.auth');
    }

    public function index()
    {
        $func = "eventtype_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "eventtype_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh mục loại sự kiện</li>';
        
        $EventTypes = EventType::orderBy('id', 'DESC')->paginate($this->pagesize);

        return view('Events::event_type.index', compact('active_menu', 'breadcrumb', 'EventTypes'));
    }

    public function search(Request $request)
    {
        $func = "eventtype_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        if ($request->datasearch) {
            $active_menu = "eventtype_list";
            $searchdata  = $request->datasearch;
            $EventTypes = EventType::where('title', 'LIKE', '%' . $request->datasearch . '%')
                                    ->paginate($this->pagesize);

            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.event_type.index') . '">Danh sách loại sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>';

            return view('Events::event_type.search', compact('EventTypes', 'active_menu', 'breadcrumb', 'searchdata'));
        } else {
            return redirect()->route('admin.event_type.index')->with('nothing');
        }
    }

    public function create()
    {
        $func = 'eventtype_add';
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "eventtype_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.event_type.index') . '">Danh sách loại sự kiện</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo loại sự kiện</li>';

        return view('Events::event_type.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        $func = "eventtype_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'location_type' => 'required|in:outdoor,indoor',
            'location_address'=>'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->input('title'));
        $slug_count = EventType::where('slug', $slug)->count();
        if ($slug_count > 0) {
            $slug .= time() . '-' . $slug;
        }
        $data['slug'] = $slug;
        
        $status = EventType::create($data);
        if ($status) {
            return redirect()->route('admin.event_type.index')->with('success', 'Thêm loại sự kiện thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    public function edit(string $id)
    {
        $func = "eventtype_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $EventTypes = EventType::find($id);
        if ($EventTypes) {
            $active_menu = "eventtype_list";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.event_type.index') . '">Danh sách loại sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Điều chỉnh loại sự kiện</li>';

            return view('Events::event_type.edit', compact('breadcrumb', 'EventTypes', 'active_menu'));
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, string $id)
    {
        $func = "eventtype_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $EventTypes = EventType::find($id);
        if ($EventTypes) {
            $this->validate($request, [
                'title' => 'string|required',
                'location_type' => 'required|in:outdoor,indoor',
                'location_address'=>'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            $data = $request->all();
            $status = $EventTypes->fill($data)->save();
            if ($status) {
                return redirect()->route('admin.event_type.index')->with('success', 'Cập nhật loại sự kiện thành công');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    public function destroy(string $id)
    {
        $func = "eventtype_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $EventTypes = EventType::find($id);
        if ($EventTypes) {
            $status = $EventTypes->delete();
            if ($status) {
                return redirect()->route('admin.event_type.index')->with('success', 'Xóa loại sự kiện thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $eventType = EventType::findOrFail($request->id);

            // Cập nhật trạng thái
            $eventType->status = $request->mode ? 'active' : 'inactive';
            $eventType->save();

            // Trả về phản hồi thành công
            return response()->json([
                'status' => true,
                'msg' => 'Cập nhật trạng thái thành công!',
            ]);
        } catch (\Exception $e) {
            // Trả về phản hồi lỗi
            return response()->json([
                'status' => false,
                'msg' => 'Không thể thay đổi trạng thái. Vui lòng thử lại!',
            ]);
        }
    }
}
