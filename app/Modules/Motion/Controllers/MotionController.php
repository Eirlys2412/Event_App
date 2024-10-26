<?php

namespace App\Modules\Motion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Motion\Models\Motion;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag; // Nếu sử dụng tags cho motion

class MotionController extends Controller
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
        $func = "motion_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        // Lấy danh sách Motion và phân trang
        $motions = Motion::orderBy('id', 'DESC')->paginate($this->pagesize);

        // Chuẩn bị dữ liệu cho breadcrumb và active menu
        $active_menu = "motion_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách Motion</li';

        return view('Motion::motion.index', compact('motions', 'breadcrumb', 'active_menu'));
    }


    public function create()
    {
        // Kiểm tra quyền truy cập
        $func = "motion_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        // Lấy danh sách tags có trạng thái active
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();

        // Chuẩn bị dữ liệu cho active menu và breadcrumb
        $active_menu = "motion_add";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('admin.motion.index') . '">Motion</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tạo Motion</li>';

        return view('Motion::motion.create', compact('tags', 'breadcrumb', 'active_menu'));
    }


    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string', // Có thể là URL hoặc emoji
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tạo mới một Motion
        $motion = new Motion();
        $motion->title = $request->input('title');
        $motion->icon = $request->input('icon');

        // Lưu vào cơ sở dữ liệu
        $motion->save();

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.motion.index')->with('success', 'Motion đã được thêm thành công!');
    }
    

    public function edit($id)
{
    // Kiểm tra quyền truy cập
    if (!$this->check_function("motion_edit")) {
        return redirect()->route('unauthorized');
    }

    // Lấy motion cần chỉnh sửa
    $motion = Motion::findOrFail($id);

    // Đặt active menu (thay đổi theo ngữ cảnh của bạn)
    $active_menu = 'motion'; // Hoặc giá trị tương ứng

    // Trả về view với dữ liệu cần thiết
    return view('Motion::motion.edit', compact('motion', 'active_menu'));
}

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'required|string', // Có thể là URL hoặc emoji
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Lấy motion cần cập nhật
        $motion = Motion::findOrFail($id);

        // Cập nhật thông tin motion
        $motion->title = $request->input('title');
        $motion->icon = $request->input('icon');

        // Lưu vào cơ sở dữ liệu
        $motion->save();

        // Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('admin.motion.index')->with('success', 'Motion đã được cập nhật thành công!');
    }

    public function destroy($id)
{
    // Tìm motion theo ID
    $motion = Motion::findOrFail($id);

    // Xóa motion
    $motion->delete();

    // Chuyển hướng về danh sách motion với thông báo
    return redirect()->route('admin.motion.index')->with('success', 'Motion đã được xóa thành công.');
}
}
