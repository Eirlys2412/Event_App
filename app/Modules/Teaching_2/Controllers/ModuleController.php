<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modules\Teaching_2\Models\Module;
use App\Modules\Teaching_2\Models\HinhThucThi;


class ModuleController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "module_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="module_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách học phần</li>';  
        $module = Module::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Teaching_2::module.index',compact('module','breadcrumb','active_menu'));
    }


    public function create()
    {
        $hinhthucthi = HinhThucThi::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm học phần</li>';
        $active_menu = "module_type_add";
        return view('Teaching_2::module.create', compact('breadcrumb', 'active_menu','hinhthucthi'));
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

    // Xử lý tệp hình ảnh
    // $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
    // $path = $request->file('photo')->storeAs('module', $fileName, 'public');
    // $requestData['photo'] = '/storage/' . $path; // Cập nhật đường dẫn tệp tin trong dữ liệu
    $helpController = new \App\Http\Controllers\FilesController();
    $requestData['photo'] = $helpController->store($request->file('photo'));

    // Lưu dữ liệu vào cơ sở dữ liệu
    Module::create($requestData);
    return redirect()->route('admin.module.index')->with('thongbao', 'Tạo học phần thành công.');
}

    public function edit($id)
    {
        $module = Module::findOrFail($id); // Lấy bản ghi theo ID
        $hinhthucthi = HinhThucThi::all(); // Lấy tất cả hình thức thi
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa học phần</li>';
        $active_menu = "resource_type_edit";
        return view('Teaching_2::module.edit', compact('module','hinhthucthi', 'breadcrumb', 'active_menu'));
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
    $module = Module::findOrFail($id);

    // Lấy tất cả dữ liệu từ yêu cầu
    $requestData = $request->all();

    // Xử lý tệp hình ảnh nếu có
    if ($request->hasFile('photo')) {
        // Xóa tệp hình ảnh cũ nếu tồn tại
        if (Storage::disk('public')->exists(str_replace('storage/', '', $module->photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $module->photo));
        }

        // Lưu tệp hình ảnh mới
        $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $path = $request->file('photo')->storeAs('module', $fileName, 'public');
        $requestData['photo'] = '/storage/' . $path; // Cập nhật đường dẫn tệp tin trong dữ liệu
    } else {
        // Nếu không có tệp hình ảnh mới, giữ nguyên đường dẫn tệp cũ
        $requestData['photo'] = $module->photo;
    }

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $module->update($requestData);

    return redirect()->route('admin.module.index')->with('thongbao', 'Cập nhật học phần thành công.');
}

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        if (Storage::disk('public')->exists(str_replace('storage/', '', $module->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $module->path));
        }
        $module->delete();
        return redirect()->route('admin.module.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function moduleSearch(Request $request)
    {
        $func = "module_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="module_list";
            $searchdata =$request->datasearch;  
            $module = DB::table('modules')->where('title','LIKE','%'.$request->datasearch.'%')
            ->paginate($this->pagesize)->withQueryString();

            // Lấy danh sách module mà không có người dùng tương ứng
            // $userModuleIds = DB::table('modules')
            // ->join('users', 'users.id', '=', 'modules.user_id')
            // ->where('users.code', 'LIKE', '%' . $request->datasearch . '%')
            // ->pluck('modules.id')
            // ->toArray();

            // $module = DB::table('modules')
            // ->whereNotIn('id', $userModuleIds)
            // ->select('title', 'tinchi', 'id')
            // ->paginate($this->pagesize)
            // ->withQueryString();

            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.module.index').'">Học phần</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Tìm kiếm </li>';
            return view('Teaching_2::module.search',compact('module','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('admin.module.index')->with('success','Không có thông tin tìm kiếm!');
        }
        // Trả về view 'Recommend::recommend.search'
        // return view('Recommend::recommend.search'); // Lưu ý 'search' phải đúng tên file view
    }
}