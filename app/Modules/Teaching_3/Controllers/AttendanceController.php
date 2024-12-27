<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Teaching_3\Models\Attendance;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\DiaDiem;
use App\Models\User;

class AttendanceController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "attendance_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="attendance_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách điểm danh</li>';  
        $diemdanh = Attendance::with(['thoikhoabieu','thoikhoabieu.phancong','thoikhoabieu.phancong.giangvien','thoikhoabieu.phancong.hocphan'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $users = User::all();
        return view('Teaching_3::diemdanh.index', compact('users','diemdanh','breadcrumb', 'active_menu'));
    }

    public function show($id)
    {
        $active_menu="attendance_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết thời khóa biểu điểm danh</li>';  

        $diemdanh = Attendance::with([
            'thoikhoabieu',
            'thoikhoabieu.phancong',
            'thoikhoabieu.phancong.giangvien',
            'thoikhoabieu.phancong.hocphan',
            'thoikhoabieu.diaDiem'
        ])->findOrFail($id); 
        $users = User::all();
        return view('Teaching_3::diemdanh.show', compact('users','diemdanh','breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $func = "attendance_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu = "attendance_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm danh sách điểm danh</li>';
        // $diemdanh = Attendance::with(['thoikhoabieu','thoikhoabieu.phancong','thoikhoabieu.phancong.giangvien','thoikhoabieu.phancong.hocphan'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $thoikhoabieu = ThoiKhoaBieu::with(['phancong','phancong.giangvien','phancong.hocphan'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $user = User::all();
        return view('Teaching_3::diemdanh.create', compact('user','thoikhoabieu','breadcrumb', 'active_menu'));
    }
  
    public function store(Request $request)
{
    // Xác thực dữ liệu nhập vào
    $request->validate([
        'tkb_id' => 'required|integer|exists:thoi_khoa_bieus,id', // Kiểm tra xem `tkb_id` có tồn tại trong bảng `thoi_khoa_bieus`
        'user_list' => 'required|array', // Danh sách người dùng phải là một mảng
        'user_list.*' => 'integer|exists:users,id', // Mỗi phần tử trong danh sách phải là một ID hợp lệ
    ]);

    // Lấy thời gian hiện tại
    $currentTime = now()->format('Y-m-d H:i:s');

    // Chuẩn bị dữ liệu điểm danh
    $attendanceData = [];
    foreach ($request->user_list as $userId) {
        $attendanceData[] = [
            'user_id' => $userId,
            'time' => $currentTime, // Gán thời gian điểm danh
        ];
    }

    // Lấy tất cả dữ liệu từ yêu cầu, ngoại trừ 'user_list'
    $requestData = $request->except('user_list'); // Loại bỏ trường 'user_list' khỏi dữ liệu yêu cầu

    // Lưu dữ liệu vào cơ sở dữ liệu (chỉ lưu 'tkb_id' và các trường khác từ $requestData)
    $diemdanh = Attendance::create($requestData);

    // Chuyển mảng 'attendanceData' thành JSON và lưu vào trường 'user_list'
    $diemdanh->user_list = json_encode($attendanceData); // Chuyển mảng thành JSON
    $diemdanh->save(); // Lưu lại bản ghi

    // Kiểm tra nếu lưu thành công
    if ($diemdanh) {
        return redirect()->route('admin.diemdanh.index')->with('thongbao', 'Tạo điểm danh thành công.');
    } else {
        return back()->with('error', 'Có lỗi xảy ra!');
    }
}

    public function destroy($id)
    {
        $diemdanh = Attendance::findOrFail($id);
        $diemdanh->delete();
        return redirect()->route('admin.diemdanh.index')->with('thongbao', 'Xóa điểm danh thành công.');
    }
    public function edit($id){
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa điểm danh</li>';
        $active_menu = "attendance_edit";
        $diemdanh = Attendance::with([
            'thoikhoabieu',
            'thoikhoabieu.phancong',
            'thoikhoabieu.phancong.giangvien',
            'thoikhoabieu.phancong.hocphan',
            'thoikhoabieu.diaDiem'
        ])->findOrFail($id); 
        $thoikhoabieu = ThoiKhoaBieu::with(['phancong','phancong.giangvien','phancong.hocphan'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $user = User::all();
        return view('Teaching_3::diemdanh.edit', compact('diemdanh','user','thoikhoabieu','breadcrumb', 'active_menu'));
    }
    public function update(Request $request, $id)
{
    // Xác thực dữ liệu nhập vào
    $request->validate([
        'tkb_id' => 'required|integer|exists:thoi_khoa_bieus,id', // Kiểm tra xem `tkb_id` có tồn tại trong bảng `thoi_khoa_bieus`
        'user_list' => 'required|array', // Danh sách người dùng phải là một mảng
        'user_list.*' => 'integer|exists:users,id', // Mỗi phần tử trong danh sách phải là một ID hợp lệ
    ]);

    // Lấy thời gian hiện tại
    $currentTime = now()->format('Y-m-d H:i:s');

    // Chuẩn bị dữ liệu điểm danh
    $attendanceData = [];
    foreach ($request->user_list as $userId) {
        $attendanceData[] = [
            'user_id' => $userId,
            'time' => $currentTime, // Gán thời gian điểm danh
        ];
    }

    // Lấy bản ghi điểm danh cần cập nhật
    $diemdanh = Attendance::findOrFail($id);

    // Lấy tất cả dữ liệu từ yêu cầu, ngoại trừ 'user_list'
    $requestData = $request->except('user_list'); // Loại bỏ trường 'user_list' khỏi dữ liệu yêu cầu

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $diemdanh->update($requestData);

    // Chuyển mảng 'attendanceData' thành JSON và lưu vào trường 'user_list'
    $diemdanh->user_list = json_encode($attendanceData); // Chuyển mảng thành JSON
    $diemdanh->save(); // Lưu lại bản ghi

    // Kiểm tra nếu cập nhật thành công
    if ($diemdanh) {
        return redirect()->route('admin.diemdanh.index')->with('thongbao', 'Sửa điểm danh thành công.');
    } else {
        return back()->with('error', 'Có lỗi xảy ra!');
    }
}
}
