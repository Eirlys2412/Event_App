<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\PhanCong;
// use App\Modules\Teaching_3\Models\DiaDiem;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Models\Tag;
use App\Models\TagDiadiem;
use App\Modules\Teaching_3\Models\DiaDiem;

class ThoiKhoaBieuController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "thoikhoabieu_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="thoikhoabieu_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách thời khóa biểu</li>';  
        $thoikhoabieu = ThoiKhoaBieu::with(['phanCong'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $diadiem = DiaDiem::all();

        return view('Teaching_3::thoikhoabieu.index', compact('diadiem','hocphan','teacher','thoikhoabieu','breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $func = "thoikhoabieu_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu = "thoikhoabieu_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm thời khóa biểu</li>';
        $phancong  = PhanCong::all();
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $diadiem  = DiaDiem::all();
        // $diadiem = DiaDiem::all();
        return view('Teaching_3::thoikhoabieu.create', compact('diadiem','hocphan','teacher','phancong','breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'phancong_id' => 'required|integer|exists:phancong,id', // Kiểm tra xem `phancong_id` có tồn tại trong bảng `phancong`
            'diadiem_id' => 'required|integer|exists:dia_diem,id', // Kiểm tra xem `phancong_id` có tồn tại trong bảng `phancong`
            'buoi' => 'required|string|in:Sáng,Chiều,Tối', // Buổi học phải là 1 trong các giá trị hợp lệ
            'ngay' => 'required|date', // Phải là một ngày hợp lệ
            'tietdau' => 'required|integer', // Tiết bắt đầu (giới hạn từ 1 đến 10, bạn có thể điều chỉnh)
            'tietcuoi' => 'required|integer', // Tiết kết thúc phải lớn hơn hoặc bằng `tietdau`
        ]);
        

        // Lấy tất cả dữ liệu từ yêu cầu
        $requestData = $request->all();
        // Lưu dữ liệu vào cơ sở dữ liệu
        $thoikhoabieu = ThoiKhoaBieu::create($requestData);
        $thoikhoabieu->save();

        if($thoikhoabieu){
            return redirect()->route('admin.thoikhoabieu.index')->with('thongbao', 'Tạo thời khóa biểu thành công.');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }
    public function destroy($id)
    {
        $thoikhoabieu = ThoiKhoaBieu::findOrFail($id);
        $thoikhoabieu->delete();
        return redirect()->route('admin.thoikhoabieu.index')->with('thongbao', 'Xóa thời khóa biểu thành công.');
    }
    public function edit($id){
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa thời khóa biểu</li>';
        $active_menu = "thoikhoabieu_edit";
    
        $diadiem = DiaDiem::all();
        $phancong  = PhanCong::all();
        $teacher  = teacher::all();
        $hocphan  = HocPhan::all();
        $thoikhoabieu = ThoiKhoaBieu::findOrFail($id);
        return view('Teaching_3::thoikhoabieu.edit', compact('diadiem','thoikhoabieu','hocphan','teacher','phancong','breadcrumb', 'active_menu'));
    }
    public function update(Request $request, $id){
        $thoikhoabieu = ThoiKhoaBieu::find($id);

       // Xác thực dữ liệu nhập vào
       $request->validate([
        'phancong_id' => 'required|integer|exists:phancong,id', // Kiểm tra xem `phancong_id` có tồn tại trong bảng `phancong`
        'diadiem_id' => 'required|integer|exists:dia_diem,id', // Kiểm tra xem `diadiem_id` có tồn tại trong bảng `dia_diem`
        'buoi' => 'required|string|in:Sáng,Chiều,Tối', // Buổi học phải là 1 trong các giá trị hợp lệ
        'ngay' => 'required|date', // Phải là một ngày hợp lệ
        'tietdau' => 'required|integer', // Tiết bắt đầu (giới hạn từ 1 đến 10, bạn có thể điều chỉnh)
        'tietcuoi' => 'required|integer', // Tiết kết thúc phải lớn hơn hoặc bằng `tietdau`
        ]);

        // Lấy dữ liệu từ yêu cầu
        $requestData = $request->all();
        $thoikhoabieu->update($requestData);

        return redirect()->route('admin.thoikhoabieu.index')->with('thongbao', 'Sửa thời khóa biểu thành công.');
    }
}
