<?php

namespace App\Modules\Major\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Major\Models\Major;

class MajorController extends Controller
{
    // Pagination settings
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    // List all majors
    public function index()
    {
        $func = "major_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "major_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách chuyên ngành</li>';
        
        $majors = Major::orderBy('id', 'DESC')->paginate($this->pagesize);
        
        return view('Major::major.index', compact('majors', 'breadcrumb', 'active_menu'));
    }

    // Show form to create a new major
    public function create()
    {
        $func = "major_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $data['active_menu'] = "major_add";
        $data['breadcrumb'] = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.major.index') . '">Chuyên ngành</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo chuyên ngành</li>';

        return view('Major::major.create', $data);
    }

    // Store a new major
    public function store(Request $request)
    {
        $func = "major_add";
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
        $slug_count = Major::where('slug', $slug)->count();
        if ($slug_count > 0) {
            $slug .= time() . '-' . $slug;
        }
        $data['slug'] = $slug;
        $data['status'] = ($request->input('status') == 'active') ? 1 : 0;
        $data['user_id'] = auth()->user()->id;

        $major = Major::create($data);

        if ($major) {
            return redirect()->route('admin.major.index')->with('success', 'Tạo chuyên ngành thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    // Show form to edit an existing major
    public function edit(string $id)
    {
        $func = "major_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $major = Major::find($id);
        if ($major) {
            $active_menu = "major_edit";
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.major.index') . '">Chuyên ngành</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa chuyên ngành</li>';

            return view('Major::major.edit', compact('breadcrumb', 'major', 'active_menu'));
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Update an existing major
    public function update(Request $request, string $id)
    {
        $func = "major_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $major = Major::find($id);
        if ($major) {
            $this->validate($request, [
                'title' => 'string|required',
                'description' => 'string|required',
                'status' => 'required|in:active,inactive',
            ]);

            $data = $request->all();
            $data['status'] = ($request->input('status') == 'active') ? 1 : 0;

            $status = $major->fill($data)->save();

            if ($status) {
                return redirect()->route('admin.major.index')->with('success', 'Cập nhật chuyên ngành thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Delete a major
    public function destroy(string $id)
    {
        $func = "major_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $major = Major::find($id);
        if ($major) {
            $status = $major->delete();
            if ($status) {
                return redirect()->route('admin.major.index')->with('success', 'Xóa chuyên ngành thành công!');
            } else {
                return back()->with('error', 'Có lỗi xảy ra!');
            }
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    // Toggle status of a major
    public function majorStatus(Request $request)
    {
        $func = "major_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $status = $request->mode == 'true' ? 1 : 0;
        DB::table('majors')->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['msg' => "Cập nhật thành công", 'status' => true]);
    }
}
