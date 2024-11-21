<?php
namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao;
use Illuminate\Support\Facades\Redis;
use Symfony\Contracts\Service\Attribute\Required;
use App\Modules\Teaching_1\Models\Nganh;

class ChuongTrinhDaoTaoController extends Controller
{
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('admin.auth');

    }
    //HIEN THI DANH SACH
    public function index()
    {
        $func = "ChuongTrinhDaoTao_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = 'ChuongTrinhDaoTao_list';
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách Chương trình Đào tạo</li>';

        // Load mối quan hệ nganh
        $programs = ChuongTrinhDaoTao::with('nganh') // Load tên ngành
            ->orderBy('id', 'asc')
            ->paginate($this->pagesize);

        return view('Teaching_2::chuong_trinh_dao_tao.index', compact('programs', 'breadcrumb', 'active_menu'));
    }

    //hien thi form tao moi chuongtrinhdaotao
    public function create()
    {
        $func = "ChuongTrinhDaoTao_add";
        if(!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "ChuongTrinhDaoTao_add";
        $nganhList = Nganh::all(); // Lấy danh sách ngành từ bảng `nganh`
        $breadcrumb= '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.chuong_trinh_dao_tao.index') . '">Danh sách ChuongTrinhDaoTao</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo mới ChuongTrinhDaoTao</li>';
        return view('Teaching_2::chuong_trinh_dao_tao.create', compact('breadcrumb', 'active_menu','nganhList'));

    }

    public function store(Request $request)
    {
        $func = 'ChuongTrinhDaoTao_add';
        if(!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        $this->validate($request,[
            'nganh_id' => 'required|exists:nganh,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'tong_tin_chi' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive'
        ]);
        $data = $request->all();
        $programs = ChuongTrinhDaoTao::create($data);
        if($programs) {
            return redirect()->route('admin.chuong_trinh_dao_tao.index')->with("success",'Chương trình đào tạo create successfully');
        }
        else {
            return back()->with('error', 'Failes to create chươn trình đào tạo.')->withInput();
        }
    }

    public function edit(string $id)
    {
        $func = "ChuongTrinhDaoTao_edit";
        if(!$this->check_function($func)) {
            return redirect()->route('unthorized');
        }
        $programs = ChuongTrinhDaoTao::find($id);
        if($programs) {
            $active_menu = "ChuongTrinhDaoTao_list";
            $nganhList = Nganh::all(); // Lấy danh sách ngành
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="' . route('admin.chuong_trinh_dao_tao.index') . '">Danh sách VoteItems</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Chỉnh sửa chương trình đào tạo</li>';
            return view('Teaching_2::chuong_trinh_dao_tao.edit',compact('breadcrumb','active_menu','programs','nganhList'));
        }
        else{
            return back()->with('error', 'không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, string $id)
    {
        $func = "ChuongTrinhDaoTao_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }

        $programs = ChuongTrinhDaoTao::find($id);
        if($programs)
        {
            $this->validate($request, [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tong_tin_chi' => 'required|integer|min:0',
                'status'=>'required|in:active,inactive',
            ]);
            $data = $request->all();
            $status = $programs->fill($data)->save();
            if($status) {
                return redirect()->route('admin.chuong_trinh_dao_tao.index')->with('success','cập nhật thành công');
            } else {
                return back()->with('error','có lỗi xảy ra!');
            }
        } else {
            return back()->with('error','không tìm thấy dữ liệu');
        }
    }

    public function destroy(string $id)
    {
        $func = "ChuongTrinhDaoTao_delete";
        if(!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $programs = ChuongTrinhDaoTao::find($id);
        if($programs) {
            $status = $programs->delete();
            if($status) {
                return redirect()->route('admin.chuong_trinh_dao_tao.index')->with('success','xóa dữ liệu thành công');
            }else {
                return back()->with('error','có lỗi xảy ra!');
            }
        }else {
            return back()->with('error', 'không tìm thấy dữ liệu');
        }
    }

    public function ChuongTrinhDaoTaoStatus(Request $request) {
        if ($request->mode == 'true') {
            DB::table('ChuongTrinhDaoTao')->where('id', $request->id)->update(['status' => 'active']);
        } else {
            DB::table('ChuongTrinhDaoTao')->where('id', $request->id)->update(['status' => 'inactive']);
        }
        return response()->json(['msg' => "Cập nhật trạng thái thành công", 'status' => true]);
    }

    public function search(Request $request)
{
    $query = $request->input('datasearch');
    $active_menu = "ChuongTrinhDaoTao_list";

    $programs = ChuongTrinhDaoTao::where('id', 'LIKE', "%{$query}%")
        ->orWhere('title', 'LIKE', "%{$query}%")
        ->paginate($this->pagesize);

    $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item active" aria-current="page">Danh sách Chương trình đào tạo</li>';

    return view('Teaching_2::chuong_trinh_dao_tao.index', compact('programs', 'breadcrumb', 'active_menu'));
}
}