<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\HinhThucThi;


class HocPhanController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "hocphan_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="hocphan_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách học phần</li>';  
        $hocphan = HocPhan::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Teaching_2::hocphan.index',compact('hocphan','breadcrumb','active_menu'));
    }


    public function create()
    {
        $hinhthucthi = HinhThucThi::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm học phần</li>';
        $active_menu = "module_type_add";
        return view('Teaching_2::hocphan.create', compact('breadcrumb', 'active_menu','hinhthucthi'));
    }

    public function store(Request $request)
{
    // Xác thực dữ liệu nhập vào
    $request->validate([
        'title' => 'required|string|max:255',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'code' => 'required|string|max:255',
        'content' => 'string|required', // Yêu cầu nội dung
        'summary' => 'nullable|string',
        'tinchi' => 'required|string|max:50',
        'hinhthucthi' => 'required|string|max:50',
    ]);

    // Lấy tất cả dữ liệu từ yêu cầu
    $requestData = $request->all();

    $helpController = new \App\Http\Controllers\FilesController();
    $requestData['photo'] = $helpController->store($request->file('photo'));

    // Lưu dữ liệu vào cơ sở dữ liệu
    HocPhan::create($requestData);
    return redirect()->route('admin.hocphan.index')->with('thongbao', 'Tạo học phần thành công.');
}

    public function edit($id)
    {
        $hocphan = HocPhan::findOrFail($id); // Lấy bản ghi theo ID
        $hinhthucthi = HinhThucThi::all(); // Lấy tất cả hình thức thi
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa học phần</li>';
        $active_menu = "resource_type_edit";
        return view('Teaching_2::hocphan.edit', compact('hocphan','hinhthucthi', 'breadcrumb', 'active_menu'));
    }
   
    public function update(Request $request, $id)
{
    // Xác thực dữ liệu nhập vào
    $request->validate([
        'title' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Chỉ yêu cầu nếu có tệp hình ảnh
        'code' => 'required|string|max:255',
        'content' => 'string|required', // Yêu cầu nội dung
        'summary' => 'nullable|string',
        'tinchi' => 'required|string|max:50',
        'hinhthucthi' => 'required|string|max:50',
    ]);

    // Tìm bản ghi theo ID
    $hocphan = HocPhan::findOrFail($id);

    // Lấy tất cả dữ liệu từ yêu cầu
    $requestData = $request->all();

    // Xử lý tệp hình ảnh nếu có
    if ($request->hasFile('photo')) {
        // Xóa tệp hình ảnh cũ nếu tồn tại
        if (Storage::disk('public')->exists(str_replace('storage/', '', $hocphan->photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $hocphan->photo));
        }

        // Lưu tệp hình ảnh mới
        $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $path = $request->file('photo')->storeAs('hocphan', $fileName, 'public');
        $requestData['photo'] = '/storage/' . $path; // Cập nhật đường dẫn tệp tin trong dữ liệu
    } else {
        // Nếu không có tệp hình ảnh mới, giữ nguyên đường dẫn tệp cũ
        $requestData['photo'] = $hocphan->photo;
    }

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $hocphan->update($requestData);

    return redirect()->route('admin.hocphan.index')->with('thongbao', 'Cập nhật học phần thành công.');
}

    public function destroy($id)
    {
        $hocphan = HocPhan::findOrFail($id);
        if (Storage::disk('public')->exists(str_replace('storage/', '', $hocphan->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $hocphan->path));
        }
        $hocphan->delete();
        return redirect()->route('admin.hocphan.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function moduleSearch(Request $request)
    {
        $func = "hocphan_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="hocphan_list";
            $searchdata =$request->datasearch;  
            $hocphan = DB::table('hoc_phans')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();          

            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.hocphan.index').'">Học phần</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Tìm kiếm </li>';
            return view('Teaching_2::hocphan.search',compact('hocphan','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('admin.hocphan.index')->with('success','Không có thông tin tìm kiếm!');
        }
        // Trả về view 'Recommend::recommend.search'
        // return view('Recommend::recommend.search'); // Lưu ý 'search' phải đúng tên file view
    }
}