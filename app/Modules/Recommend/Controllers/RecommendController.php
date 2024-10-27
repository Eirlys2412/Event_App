<?php

namespace App\Modules\Recommend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modules\Recommend\Models\Module;


class RecommendController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
    {
        $func = "recommend_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="recommend_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách học phần</li>';  
        $module = Module::orderBy('id','DESC')->paginate($this->pagesize);
        return view('Recommend::recommend.index',compact('module','breadcrumb','active_menu'));
    }


    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm học phần</li>';
        $active_menu = "resource_type_add";
        return view('Recommend::recommend.create', compact('breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        Module::create($request->all());
        return redirect()->route('admin.recommend.index')->with('thongbao', 'Tạo học phần thành công.');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id); // Lấy bản ghi theo ID
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa học phần</li>';
        $active_menu = "resource_type_edit";
        return view('Recommend::recommend.edit', compact('module', 'breadcrumb', 'active_menu'));
    }
   
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tinchi' => 'string|max:50',
        ]);

        $recommend = Module::findOrFail($id);
        $recommend->update($request->all());

        return redirect()->route('admin.recommend.index')->with('thongbao', 'Cập nhật học phần thành công.');
    }

    public function destroy($id)
    {
        $recommend = Module::findOrFail($id);
        if (Storage::disk('public')->exists(str_replace('storage/', '', $recommend->path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $recommend->path));
        }
        $recommend->delete();
        return redirect()->route('admin.recommend.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function recommendSearch(Request $request)
    {
        $func = "recommend_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="recommend_list";
            $searchdata =$request->datasearch;  
            // $module = DB::table('modules')->where('name','LIKE','%'.$request->datasearch.'%')
            // ->paginate($this->pagesize)->withQueryString();

            // Lấy danh sách module mà không có người dùng tương ứng
            $userModuleIds = DB::table('modules')
            ->join('users', 'users.id', '=', 'modules.user_id')
            ->where('users.code', 'LIKE', '%' . $request->datasearch . '%')
            ->pluck('modules.id')
            ->toArray();

            $module = DB::table('modules')
            ->whereNotIn('id', $userModuleIds)
            ->select('name', 'tinchi', 'id')
            ->paginate($this->pagesize)
            ->withQueryString();

            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.recommend.index').'">Học phần</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Tìm kiếm </li>';
            return view('Recommend::recommend.search',compact('module','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('admin.recommend.index')->with('success','Không có thông tin tìm kiếm!');
        }
        // Trả về view 'Recommend::recommend.search'
        // return view('Recommend::recommend.search'); // Lưu ý 'search' phải đúng tên file view
    }
}