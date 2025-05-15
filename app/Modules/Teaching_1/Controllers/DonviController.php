<?php

namespace App\Modules\Teaching_1\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Teaching_1\Models\Donvi;
use Illuminate\Support\Facades\Validator;

class DonviController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

   // Danh sách các đơn vị
   public function index()
   {
       // Kiểm tra quyền truy cập
       $func = "donvi_list";
       if (!$this->check_function($func)) {
           return redirect()->route('unauthorized');
       }

       // Lấy danh sách Donvi và phân trang
       $donviList = Donvi::orderBy('id', 'DESC')->paginate($this->pagesize);

       // Chuẩn bị dữ liệu cho breadcrumb và active menu
       $active_menu = "donvi_list";
       $breadcrumb = '
           <li class="breadcrumb-item"><a href="#">/</a></li>
           <li class="breadcrumb-item active" aria-current="page">Danh sách Đơn vị</li>';

       return view('Teaching_1::donvi.index', compact('donviList', 'breadcrumb', 'active_menu'));
   }

   // Tạo mới đơn vị
   public function create()
{
    // Kiểm tra quyền truy cập
    $func = "donvi_add";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    // Lấy tất cả các đơn vị để hiển thị trong danh sách đơn vị cha
    $donviList = Donvi::orderBy('title', 'ASC')->get();

    // Chuẩn bị dữ liệu cho active menu và breadcrumb
    $active_menu = "donvi_add";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.donvi.index') . '">Đơn vị</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo Đơn vị</li>';

    // Truyền biến donviList vào view
    return view('Teaching_1::donvi.create', compact('donviList', 'breadcrumb', 'active_menu'));
}

   


   // Lưu đơn vị mới

   public function store(Request $request)
{
    // Xác thực dữ liệu
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:donvi,id',  // Kiểm tra đơn vị cha
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Tạo slug tự động từ title
    $slug = Str::slug($request->input('title'), '-');

    // Tạo mới đơn vị
    $donvi = new Donvi();
    $donvi->title = $request->input('title');
    $donvi->slug = $slug;
    $donvi->parent_id = $request->input('parent_id');

    // Lưu vào cơ sở dữ liệu
    $donvi->save();

    // Kiểm tra nếu có đơn vị cha
    if ($request->has('parent_id')) {
        $parent = Donvi::find($request->input('parent_id'));

        // Lấy dữ liệu hiện tại của children_id và thêm đơn vị mới vào
        $childrenId = json_decode($parent->children_id, true) ?? [];
        $childrenId[] = ['id' => $donvi->id, 'title' => $donvi->title, 'child' => []];

        // Cập nhật lại children_id của đơn vị cha
        $parent->children_id = json_encode($childrenId);
        $parent->save();
    }

    // Chuyển hướng về trang danh sách với thông báo thành công
    return redirect()->route('admin.donvi.index')->with('success', 'Đơn vị đã được thêm thành công!');
}


   // Chỉnh sửa đơn vị
   public function edit($id)
{
    // Kiểm tra quyền truy cập
    if (!$this->check_function("donvi_edit")) {
        return redirect()->route('unauthorized');
    }

    // Lấy đơn vị cần chỉnh sửa
    $donvi = Donvi::findOrFail($id);

    // Lấy danh sách tất cả các đơn vị, ngoại trừ đơn vị hiện tại
    $donviList = Donvi::where('id', '!=', $id)->orderBy('title', 'ASC')->get();

    // Lấy danh sách các đơn vị cha mà có con
    $parentIdsWithChildren = $this->getParentIdsWithChildren($id);

    // Loại bỏ các đơn vị có con trong danh sách đơn vị cha có thể chọn
    $donviList = $donviList->filter(function ($item) use ($parentIdsWithChildren) {
        return !in_array($item->id, $parentIdsWithChildren);
    });

    // Chuẩn bị dữ liệu cho active menu và breadcrumb
    $active_menu = 'donvi';
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.donvi.index') . '">Đơn vị</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Đơn vị</li>';

    // Trả về view với dữ liệu
    return view('Teaching_1::donvi.edit', compact('donvi', 'donviList', 'breadcrumb', 'active_menu'));
}

private function getParentIdsWithChildren($id)
{
    // Đệ quy để lấy tất cả các ID của đơn vị cha có con
    $parentIds = [];
    $children = Donvi::where('parent_id', $id)->get();

    foreach ($children as $child) {
        $parentIds[] = $child->id;
        // Tiếp tục kiểm tra các đơn vị con sâu
        $parentIds = array_merge($parentIds, $this->getParentIdsWithChildren($child->id));
    }

    return $parentIds;
}




   // Cập nhật đơn vị
   public function update(Request $request, $id)
{
    // Kiểm tra quyền truy cập
    if (!$this->check_function("donvi_edit")) {
        return redirect()->route('unauthorized');
    }

    // Lấy đơn vị cần cập nhật
    $donvi = Donvi::findOrFail($id);

    // Tạo slug từ title nếu không có slug
    $slug = $request->input('slug');
    if (empty($slug)) {
        $slug = Str::slug($request->input('title')); // Tạo slug từ title
    }

    // Cập nhật thông tin đơn vị
    $donvi->update([
        'title' => $request->input('title'),
        'slug' => $slug,
        'parent_id' => $request->input('parent_id'),
    ]);

    // Chuyển hướng về trang danh sách đơn vị sau khi cập nhật
    return redirect()->route('admin.donvi.index')->with('success', 'Đơn vị đã được cập nhật!');
}


   // Xóa đơn vị
   public function destroy($id)
   {
       // Tìm đơn vị theo ID
       $donvi = Donvi::findOrFail($id);

       // Xóa đơn vị
       $donvi->delete();

       // Chuyển hướng về danh sách đơn vị với thông báo
       return redirect()->route('admin.donvi.index')->with('success', 'Đơn vị đã được xóa thành công.');
   }
}
