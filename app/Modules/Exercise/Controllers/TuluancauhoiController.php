<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Exercise\Models\Tuluancauhoi;
use App\Modules\Teaching_2\Models\Module; 
use App\Modules\Teaching_2\Models\HinhThucThi;
use Illuminate\Support\Facades\Auth; 

class TuluancauhoiController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeFunction("tuluancauhoi_list");
        
        $active_menu = "tuluancauhoi_list";
        $breadcrumb = $this->generateBreadcrumb('Danh sách Câu hỏi');
    
        // Sử dụng 'module' và 'user'
        $tuluancauhois = Tuluancauhoi::with(['user', 'module'])->orderBy('id', 'DESC')->paginate($this->pagesize);
    
        return view('Exercise::tuluancauhoi.index', compact('tuluancauhois', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $modules = Module::all(); // Lấy tất cả các module
        $breadcrumb = $this->generateBreadcrumb('Thêm Câu hỏi');
        $active_menu = "tuluancauhoi_add";

        return view('Exercise::tuluancauhoi.create', compact('breadcrumb', 'active_menu', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'hocphan_id' => 'required|exists:modules,id',
        ]);
    
        $tuluancauhoi = new Tuluancauhoi();
        $tuluancauhoi->content = $request->input('content');
        $tuluancauhoi->hocphan_id = $request->input('hocphan_id');
        $tuluancauhoi->user_id = Auth::id(); // Lấy ID của người dùng đang đăng nhập
        $tuluancauhoi->save();
    
        return redirect()->route('admin.tuluancauhoi.index')->with('success', 'Câu hỏi đã được thêm thành công.');
    }

    public function edit($id)
    {
        $tuluancauhoi = Tuluancauhoi::findOrFail($id);
        $modules = Module::all();
        $breadcrumb = $this->generateBreadcrumb('Chỉnh sửa Câu hỏi');
        $active_menu = "tuluancauhoi_edit";

        return view('Exercise::tuluancauhoi.edit', compact('tuluancauhoi', 'breadcrumb', 'active_menu', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required',
            'hocphan_id' => 'required|exists:modules,id', // Chắc chắn rằng hocphan_id là chính xác
        ]);

        $tuluancauhoi = Tuluancauhoi::findOrFail($id);
        $tuluancauhoi->content = $request->input('content');
        $tuluancauhoi->hocphan_id = $request->input('hocphan_id');
        $tuluancauhoi->save();

        return redirect()->route('admin.tuluancauhoi.index')->with('success', 'Câu hỏi đã được cập nhật thành công.');
    }

    public function destroy(string $id)
    {
        $this->authorizeFunction("tuluancauhoi_delete");
    
        // Tìm câu hỏi theo ID
        $tuluancauhoi = Tuluancauhoi::findOrFail($id);
        $tuluancauhoi->delete(); // Xóa câu hỏi
    
        return redirect()->route('admin.tuluancauhoi.index')->with('success', 'Xóa câu hỏi thành công!');
    }

    public function search(Request $request)
    {
        $this->authorizeFunction("tuluancauhoi_list");

        $searchdata = $request->input('datasearch');

        if (!empty($searchdata)) {
            $active_menu = "tuluancauhoi_list";
            $tuluancauhois = Tuluancauhoi::where('content', 'LIKE', '%' . $searchdata . '%')->paginate($this->pagesize)->withQueryString();

            $breadcrumb = $this->generateBreadcrumb('Tìm kiếm');

            return view('Exercise::tuluancauhoi.search', compact('tuluancauhois', 'breadcrumb', 'searchdata', 'active_menu'));
        } else {
            return redirect()->route('admin.tuluancauhoi.index')->with('success', 'Không có thông tin tìm kiếm!');
        }
    }

    protected function authorizeFunction($func)
    {
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
    }

    public function show(string $id)
    {
        $tuluancauhoi = Tuluancauhoi::findOrFail($id);
        return view('Exercise::tuluancauhoi.show', compact('tuluancauhoi'));
    }

    protected function generateBreadcrumb($title)
    {
        return '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">' . $title . '</li>';
    }
}