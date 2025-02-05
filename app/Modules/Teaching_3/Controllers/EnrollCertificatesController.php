<?php


namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Modules\Teaching_3\Models\Attendance;
// use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
// use App\Modules\Teaching_1\Models\teacher;
// use App\Modules\Teaching_2\Models\HocPhan;
// use App\Modules\Teaching_2\Models\PhanCong;
// use App\Modules\Teaching_3\Models\DiaDiem;
use App\Models\User;
use App\Modules\Teaching_3\Models\EnrollCertificate;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_1\Models\Donvi;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_3\Models\LoaiChungchi;
use App\Modules\Teaching_2\Models\HocPhan;

class EnrollCertificatesController extends Controller
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
        $func = "enrollcertificates_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="enrollcertificates_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách chứng nhận hoàn thành khóa học</li>';  
        $chungnhan = EnrollCertificate::with([
            'user',
            'teacher',
            'donvi',
            'phancong',
            'phancong.giangvien',
            'phancong.hocphan',
            'enrollment',
            'enrollment.user',
            'loaiChungChi'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Teaching_3::enrollcertificates.index', compact('chungnhan','breadcrumb', 'active_menu'));
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
        $user = User::all();
        $teacher = teacher::all();
        $donvi = Donvi::all();
        $phancong = PhanCong::with([
            'giangvien',
            'hocphan'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);        
        // $enrollment = Enrollment::all();
        $enrollment = Enrollment::with([
            'user'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize); 
        $chungnhan = EnrollCertificate::all();
        $loaichungchi = LoaiChungchi::all();
        return view('Teaching_3::enrollcertificates.create', compact('chungnhan','phancong','loaichungchi','enrollment','donvi','teacher','user','breadcrumb', 'active_menu'));
    }
    public function store(Request $request)
{
    $func = "enrollcertificates_add";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Validate request
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'ketqua' => 'required|string',
        'nguoicap_id' => 'required|integer|exists:teacher,id',
        'donvi_id' => 'required|integer|exists:donvi,id',
        'phancong_id' => 'required|integer|exists:phancong,id',
        'loai_id' => 'required|integer|exists:loai_chungchi,id',
    ]);

    // Tự động tìm giá trị enroll_id dựa trên user_id
    $enrollment = Enrollment::where('user_id', $request->user_id)->first();

    if (!$enrollment) {
        return redirect()->back()->withErrors(['user_id' => 'Sinh viên này chưa học khóa học này nên chưa được cấp.']);
    }

    // Tạo dữ liệu với giá trị enroll_id
    $data = $request->all();
    $data['enroll_id'] = $enrollment->id;

    // Tạo mới chứng chỉ
    $chungnhan = EnrollCertificate::create($data);

    return redirect()->route('admin.enrollcertificates.index')->with('thongbao', 'Tạo chứng nhận thành công.');
}

    public function destroy($id)
    {
        $chungnhan = EnrollCertificate::findOrFail($id);
        $chungnhan->delete();
        return redirect()->route('admin.enrollcertificates.index')->with('thongbao', 'Xóa thành công.');
    }

    public function edit($id){

        $func = "enrollcertificates_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        $active_menu = "enrollcertificates_edit";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa danh sách chứng nhận hoàn thành khóa học</li>';
        $chungnhan = EnrollCertificate::with([
            'user',
            'teacher',
            'donvi',
            'phancong',
            'phancong.giangvien',
            'phancong.hocphan',
            'enrollment',
            'enrollment.user',
            'loaiChungChi'
        ])->findOrFail($id);
        $user = User::all();
        $teacher = teacher::all();
        $donvi = Donvi::all();
        $phancong = PhanCong::with([
            'giangvien',
            'hocphan'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $enrollment = Enrollment::with([
            'user'
        ])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $loaichungchi = LoaiChungchi::all();
        return view('Teaching_3::enrollcertificates.edit', compact('chungnhan','phancong','loaichungchi','enrollment','donvi','teacher','user','breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
{
    $func = "enrollcertificates_edit";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Tìm chứng nhận cần cập nhật
    $chungnhan = EnrollCertificate::findOrFail($id);

    // Validate request
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'ketqua' => 'required|string',
        'nguoicap_id' => 'required|integer|exists:teacher,id',
        'donvi_id' => 'required|integer|exists:donvi,id',
        'phancong_id' => 'required|integer|exists:phancong,id',
        'loai_id' => 'required|integer|exists:loai_chungchi,id',
    ]);

    // Tự động tìm giá trị enroll_id dựa trên user_id
    $enrollment = Enrollment::where('user_id', $request->user_id)->first();

    if (!$enrollment) {
        return redirect()->back()->withErrors(['user_id' => 'Không tìm thấy thông tin hoàn thành khóa học của người học này.']);
    }

    // Chuẩn bị dữ liệu để cập nhật
    $data = $request->all();
    $data['enroll_id'] = $enrollment->id;

    // Cập nhật chứng nhận
    $chungnhan->update($data);

    return redirect()->route('admin.enrollcertificates.index')->with('thongbao', 'Cập nhật chứng nhận thành công.');
}

}
