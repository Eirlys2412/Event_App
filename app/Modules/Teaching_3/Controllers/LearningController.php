<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
// use App\Modules\Teaching_3\Models\EnrollCertificate;
use App\Modules\Teaching_1\Models\teacher;
// use App\Modules\Teaching_1\Models\Donvi;
use App\Modules\Teaching_2\Models\PhanCong;
// use App\Modules\Teaching_3\Models\Enrollment;
// use App\Modules\Teaching_3\Models\LoaiChungchi;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_3\Models\Learning;

class LearningController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "learning_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="learning_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng đang học</li>';  
        $danghoc = Learning::with([
            'user',
            'phancong',
            'phancong.giangvien',
            'phancong.hocphan'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Teaching_3::learning.index', compact('danghoc','breadcrumb', 'active_menu'));
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
        <li class="breadcrumb-item active" aria-current="page">Thêm danh sách người dùng đang học</li>';
        $user = User::all();
        $phancong = PhanCong::with([
            'giangvien',
            'hocphan'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);        
        return view('Teaching_3::learning.create', compact('phancong','user','breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
{
    $func = "enrollcertificates_add";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Validate request
    $request->validate([
        'user_id' => 'required|integer|exists:users,id', // Phải là số nguyên và tồn tại trong bảng users
        'phancong_id' => 'required|integer|exists:phancong,id', // Phải là số nguyên và tồn tại trong bảng phancong
        // 'noidung_id' => 'required|integer|exists:noidungs,id', // Phải là số nguyên và tồn tại trong bảng noidungs
        'time_spending' => 'required|integer|min:1', // Phải là số nguyên, ít nhất là 1 phút
        'status' => 'required|string|in:started,done', // Phải là chuỗi và giá trị nằm trong ['started', 'done']
    ]);

    // Tạo dữ liệu với giá trị enroll_id
    $data = $request->all();
    // Tạo mới chứng chỉ
    $danghoc = Learning::create($data);
    return redirect()->route('admin.learning.index')->with('thongbao', 'Tạo thành công.');
}
    public function destroy($id)
    {
        $danghoc = Learning::findOrFail($id);
        $danghoc->delete();
        return redirect()->route('admin.learning.index')->with('thongbao', 'Xóa thành công.');
    }

    public function edit($id){

        $func = "enrollcertificates_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        $active_menu = "enrollcertificates_edit";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa danh sách người dùng đang học</li>';
        $danghoc = Learning::findOrFail($id);
        $user = User::all();
        $phancong = PhanCong::with([
            'giangvien',
            'hocphan'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);     
        return view('Teaching_3::learning.edit', compact('danghoc','phancong','user','breadcrumb', 'active_menu'));
    }
    public function update(Request $request, $id)
{
    $func = "enrollcertificates_edit";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Tìm chứng nhận cần cập nhật
    $danghoc = Learning::findOrFail($id);

     // Validate request
     $request->validate([
        'user_id' => 'required|integer|exists:users,id', // Phải là số nguyên và tồn tại trong bảng users
        'phancong_id' => 'required|integer|exists:phancong,id', // Phải là số nguyên và tồn tại trong bảng phancong
        // 'noidung_id' => 'required|integer|exists:noidungs,id', // Phải là số nguyên và tồn tại trong bảng noidungs
        'time_spending' => 'required|integer|min:1', // Phải là số nguyên, ít nhất là 1 phút
        'status' => 'required|string|in:started,done', // Phải là chuỗi và giá trị nằm trong ['started', 'done']
    ]);

    // Chuẩn bị dữ liệu để cập nhật
    $data = $request->all();
    // Cập nhật chứng nhận
    $danghoc->update($data);
    return redirect()->route('admin.learning.index')->with('thongbao', 'Cập nhật thành công.');
}
}
