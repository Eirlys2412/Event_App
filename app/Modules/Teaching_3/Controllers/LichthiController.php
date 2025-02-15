<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\LichThi;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_3\Models\DiaDiem;

class LichThiController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "lichthi_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="lichthi_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách thời khóa biểu</li>';  
        $lichthi = LichThi::with(['phanCong'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $diadiemList = DiaDiem::pluck('title', 'id')->toArray();

        return view('Teaching_3::lichthi.index', compact('diadiemList','hocphan','teacher','lichthi','breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $func = "lichthi_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu = "lichthi_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm thời khóa biểu</li>';
        $phancong  = PhanCong::all();
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $diadiem  = DiaDiem::all();
        // $diadiem = DiaDiem::all();
        return view('Teaching_3::lichthi.create', compact('diadiem','hocphan','teacher','phancong','breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
{
    // Xác thực dữ liệu nhập vào
    $request->validate([
        'phancong_id' => 'required|integer|exists:phancong,id',
        'buoi' => 'required|string|in:Sáng,Chiều,Tối',
        'ngay1' => 'required|date',
        'ngay2' => 'nullable|date',
        'dia_diem_thi' => 'nullable|array',
        'dia_diem_thi.*' => 'integer|exists:dia_diem,id',
    ]);

    // Lấy danh sách địa điểm thi
    $diaDiemIds = $request->input('dia_diem_thi', []);
    $locations = DiaDiem::whereIn('id', $diaDiemIds)->pluck('title')->toArray();

    // Chuẩn bị dữ liệu lưu
    $lichthiData = [
        'phancong_id' => $request->input('phancong_id'),
        'buoi' => $request->input('buoi'),
        'ngay1' => $request->input('ngay1'),
        'ngay2' => $request->input('ngay2'),
        'dia_diem_thi' => json_encode([
            'id' => implode(',', $diaDiemIds), // Lưu danh sách ID
            'location' => $locations // Lưu danh sách title
        ], JSON_UNESCAPED_UNICODE),
    ];

    // Tạo mới lịch thi
    $lichthi = LichThi::create($lichthiData);

    return redirect()->route('admin.lichthi.index')->with('thongbao', 'Tạo thời khóa biểu thành công.');
}


    

    public function edit($id){
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa thời khóa biểu</li>';
        $active_menu = "lichthi_edit";
    
        $diadiemList = DiaDiem::pluck('title', 'id')->toArray();
        $phancong  = PhanCong::all();
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $lichthi = LichThi::findOrFail($id);
        return view('Teaching_3::lichthi.edit', compact('diadiemList','lichthi','hocphan','teacher','phancong','breadcrumb', 'active_menu'));
    }

    public function update(Request $request, $id)
{
    $lichthi = LichThi::findOrFail($id);

    // Xác thực dữ liệu nhập vào
    $request->validate([
        'phancong_id' => 'required|integer|exists:phancong,id',
        'buoi' => 'required|string|in:Sáng,Chiều,Tối',
        'ngay1' => 'required|date',
        'ngay2' => 'nullable|date',
        'dia_diem_thi' => 'nullable|array',
        'dia_diem_thi.*' => 'integer|exists:dia_diem,id',
    ]);

    // Lấy danh sách địa điểm thi
    $diaDiemIds = $request->input('dia_diem_thi', []);
    $locations = DiaDiem::whereIn('id', $diaDiemIds)->pluck('title')->toArray();

    // Chuẩn bị dữ liệu cập nhật
    $lichthiData = [
        'phancong_id' => $request->input('phancong_id'),
        'buoi' => $request->input('buoi'),
        'ngay1' => $request->input('ngay1'),
        'ngay2' => $request->input('ngay2'),
        'dia_diem_thi' => json_encode([
            'id' => implode(',', $diaDiemIds), // Lưu danh sách ID
            'location' => $locations // Lưu danh sách title
        ], JSON_UNESCAPED_UNICODE),
    ];

    // Cập nhật lịch thi
    $lichthi->update($lichthiData);

    return redirect()->route('admin.lichthi.index')->with('thongbao', 'Sửa thời khóa biểu thành công.');
}


public function destroy($id)
    {
        $lichthi = LichThi::findOrFail($id);
        $lichthi->delete();
        return redirect()->route('admin.lichthi.index')->with('thongbao', 'Xóa thời khóa biểu thành công.');
    }
}
