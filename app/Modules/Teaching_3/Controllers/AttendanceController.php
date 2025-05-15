<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Teaching_3\Models\Attendance;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use App\Modules\Teaching_1\Models\Student;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\DiaDiem;
use PHPUnit\Framework\MockObject\Builder\Stub;

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
        $students = Student::all();
        return view('Teaching_3::diemdanh.index', compact('students','diemdanh','breadcrumb', 'active_menu'));
    }

    public function show($id)
{
    $diemdanh = Attendance::with([
        'thoikhoabieu',
        'thoikhoabieu.phancong',
        'thoikhoabieu.phancong.giangvien',
        'thoikhoabieu.phancong.hocphan',
        'thoikhoabieu.diaDiem'
    ])->findOrFail($id);

    $students = Student::with('user')->get(); // Lấy danh sách người học

    return view('Teaching_3::diemdanh.show', compact('diemdanh', 'students'));
}


    public function create()
{
    $func = "attendance_add";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    $active_menu = "attendance_add";
    $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item active" aria-current="page">Thêm danh sách điểm danh</li>';

    // Lấy dữ liệu thời khóa biểu kèm theo giảng viên và user
    $thoikhoabieu = ThoiKhoaBieu::with([
        'phancong',
        'phancong.giangvien.user', // Thêm 'user' để lấy full_name
        'phancong.hocphan'
    ])->orderBy('id', 'DESC')->paginate($this->pagesize);

    $students = Student::with('user')->get(); 

    return view('Teaching_3::diemdanh.create', compact('students', 'thoikhoabieu', 'breadcrumb', 'active_menu'));
}

  
    public function store(Request $request)
    {
        $request->validate([
            'tkb_id' => 'required|integer|exists:thoi_khoa_bieus,id',
            'student_list' => 'required|array',
            'student_list.*' => 'integer|exists:students,id',
        ]);
    
        $requestData = $request->except('student_list');
    
        $diemdanh = Attendance::create($requestData);
    
        // Lưu student_list đúng format JSON yêu cầu
        $diemdanh->student_list = json_encode(['student_list' => $request->student_list]);
        $diemdanh->save();
    
        return redirect()->route('admin.diemdanh.index')->with('thongbao', 'Tạo điểm danh thành công.');
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
        $students = Student::with('user')->get(); 

        return view('Teaching_3::diemdanh.edit', compact('diemdanh','students','thoikhoabieu','breadcrumb', 'active_menu'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'tkb_id' => 'required|integer|exists:thoi_khoa_bieus,id',
        'student_list' => 'required|array',
        'student_list.*' => 'integer|exists:students,id',
    ]);

    $diemdanh = Attendance::findOrFail($id);
    $requestData = $request->except('student_list');

    $diemdanh->update($requestData);

    // Cập nhật student_list đúng format JSON yêu cầu
    $diemdanh->student_list = json_encode(['student_list' => $request->student_list]);
    $diemdanh->save();

    return redirect()->route('admin.diemdanh.index')->with('thongbao', 'Sửa điểm danh thành công.');
}

}
