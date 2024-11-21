<?php

namespace App\Modules\Teaching_1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Teaching_1\Models\Nganh;

class NganhController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }
    public function index()
    {
        $this->authorizeFunction("nganh_list");
    
        $active_menu = "nganh_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách Ngành</li>';
        
        // Eager loading đơn vị
        $nganhs = Nganh::with('donvi')->orderBy('id', 'DESC')->paginate($this->pagesize);
    
        return view('Teaching_1::nganh.index', compact('nganhs', 'breadcrumb', 'active_menu'));
    }

    public function create()
{
    $this->authorizeFunction("nganh_add");

    // Lấy danh sách đơn vị từ bảng donvi mà không cần điều kiện status
    $data['donvis'] = \App\Modules\Teaching_1\Models\Donvi::orderBy('title', 'ASC')->get();
    
    $data['active_menu'] = "nganh_add";
    $data['breadcrumb'] = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.nganh.index') . '">Ngành</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tạo Ngành</li>';
    
    return view('Teaching_1::nganh.create', $data);
}

    public function store(Request $request)
{
    $this->authorizeFunction("nganh_add");

    $this->validateRequest($request);

    $data = $request->all();
    $slug = Str::slug($request->input('title'));
    $slug = $this->generateUniqueSlug($slug);

    $data['slug'] = $slug;

    $nganh = Nganh::create($data);

    return $nganh 
        ? redirect()->route('admin.nganh.index')->with('success', 'Tạo ngành thành công!')
        : back()->with('error', 'Có lỗi xảy ra!');
}

public function edit(string $id)
{
    $this->authorizeFunction("nganh_edit");

    // Lấy danh sách tất cả đơn vị từ bảng donvi
    $donvis = \App\Modules\Teaching_1\Models\Donvi::orderBy('title', 'ASC')->get();
    $nganh = Nganh::findOrFail($id);

    $active_menu = "nganh_list";
    $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.nganh.index') . '">Ngành</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Ngành</li>';

    return view('Teaching_1::nganh.edit', compact('breadcrumb', 'nganh', 'active_menu', 'donvis'));
}

public function update(Request $request, string $id)
{
    $func = "nganh_edit";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    $nganh = Nganh::find($id);
    if ($nganh) {
        // Xác thực dữ liệu
        $this->validate($request, [
            'title' => 'string|required',
            'code' => 'string|required',
            'content' => 'string|required',
            'status' => 'required|in:active,inactive',
            'donvi_id' => 'numeric|nullable',
        ]);

        // Lấy dữ liệu từ request
        $data = $request->all();
        $data['slug'] = Str::slug($request->input('title'));
        $data['slug'] = $this->generateUniqueSlug($data['slug'], $nganh->id); // Tạo slug duy nhất

        // Cập nhật dữ liệu
        $status = $nganh->fill($data)->save();

        // Trả về thông báo thành công và chuyển hướng
        return $status
            ? redirect()->route('admin.nganh.index')->with('success', 'Cập nhật thành công')
            : back()->with('error', 'Có lỗi xảy ra!');
    } else {
        return back()->with('error', 'Không tìm thấy dữ liệu');
    }
}

public function nganhStatus(Request $request)
{
    $this->validate($request, [
        'id' => 'required|exists:nganh,id',
        'mode' => 'required|in:true,false',
    ]);

    $status = $request->mode == 'true' ? 'active' : 'inactive';
    DB::table('nganh')->where('id', $request->id)->update(['status' => $status]);

    return response()->json(['msg' => "Cập nhật trạng thái thành công!", 'status' => true]);
}

public function nganhSearch(Request $request)
{
    $this->authorizeFunction("nganh_list");

    if ($request->has('datasearch') && !empty($request->datasearch)) {
        $active_menu = "nganh_list";
        $searchdata = $request->input('datasearch');

        $nganhs = DB::table('nganh')
            ->where('title', 'LIKE', '%' . $searchdata . '%')
            ->orWhere('content', 'LIKE', '%' . $searchdata . '%')
            ->paginate($this->pagesize)
            ->withQueryString();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.nganh.index') . '">Ngành</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>';

        return view('Teaching_1::nganh.search', compact('nganhs', 'breadcrumb', 'searchdata', 'active_menu'));
    } else {
        return redirect()->route('admin.nganh.index')->with('success', 'Không có thông tin tìm kiếm!');
    }
}

    // Helper methods
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'title' => 'string|required',
            'donvi_id' => 'numeric|required',
            'code' => 'string|required',
            'content' => 'string|required',
            'status' => 'required|in:active,inactive',
        ]);
    }

    protected function generateUniqueSlug($slug, $existingId = null)
    {
        // Kiểm tra số lượng slug đã tồn tại, bỏ qua ID hiện tại nếu có
        $slugCount = Nganh::where('slug', $slug)
            ->when($existingId, function ($query) use ($existingId) {
                return $query->where('id', '!=', $existingId);
            })
            ->count();
    
        return $slugCount > 0 ? $slug . '-' . time() : $slug;
    }

    protected function authorizeFunction($func)
    {
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
    }


    public function show(string $id)
{
    $nganh = Nganh::findOrFail($id);
    return view('Teaching_1::nganh.show', compact('nganh'));
}
}