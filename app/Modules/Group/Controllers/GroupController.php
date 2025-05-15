<?php



namespace App\Modules\Group\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Modules\Group\Models\Group; 
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;  // Sử dụng lớp Controller
class GroupController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('admin.auth');
    }

    public function index()
    {
        $groups = Group::orderBy('id', 'DESC')->paginate(20);
        $active_menu = 'group_list';
        return view('Group::index', compact('groups', 'active_menu'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'slug' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kiểm tra loại file
    ]);

    $group = new Group();
    $group->title = $request->input('title');
    $group->slug = $request->input('slug');
    $group->description = $request->input('description');
    $group->status = $request->input('status');
    $group->private = $request->input('private');

    // Xử lý ảnh
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        // Lưu ảnh vào thư mục 'public' trong storage
        $imagePath = $image->store('groups', 'public');
        $group->image = $imagePath; // Lưu đường dẫn ảnh vào database
    }

    $group->save();

    return redirect()->route('admin.group.index')->with('success', 'Nhóm người dùng đã được thêm thành công.');
}




    public function create()
    {
        $func = "group_add";  // Thay đổi ugroup_add thành group_add
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $active_menu = "group_add";  // Thay ugroup_add thành group_add
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="' . route('admin.group.index') . '">Nhóm</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo nhóm </li>';

        return view('Group::create', compact('breadcrumb', 'active_menu'));  // Cập nhật đường dẫn view
    }


    public function edit(string $id)
    {
        $func = "group_edit";  // Thay ugroup_edit thành group_edit
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        if ($id == 1) {
            return back()->with('error', 'Không thể điều chỉnh');
        }

        $group = Group::find($id);  // Thay UGroup bằng Group
        if ($group) {
            $active_menu = "group_list";  // Thay ugroup_list thành group_list
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="' . route('admin.group.index') . '">Nhóm</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh nhóm </li>';

            return view('Group::edit', compact('breadcrumb', 'group', 'active_menu'));
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu');
        }
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:groups,slug,' . $id,
            'status' => 'required|in:active,inactive',
            'private' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $group->title = $request->title;
        $group->slug = $request->slug;
        $group->status = $request->status;
        $group->private = $request->private;
        $group->description = $request->description;
    
        // Kiểm tra xem có tải lên ảnh mới không
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($group->image) {
                Storage::delete($group->image);
            }
            // Lưu ảnh mới
            $path = $request->file('image')->store('groups/images');
            $group->image = $path;
        }
    
        $group->save();
    
        return redirect()->route('admin.group.index')->with('success', 'Nhóm đã được cập nhật thành công!');
    }
    

    public function destroy($id)
    {
        $group = Group::findOrFail($id);  // Thay UGroup bằng Group

        try {
            if ($group->image) {
                Storage::disk('public')->delete($group->image);
            }

            $group->delete();
            return redirect()->route('admin.group.index')->with('success', 'Xóa nhóm thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.group.index')->with('error', 'Có lỗi xảy ra khi xóa nhóm!');
        }
    }
}