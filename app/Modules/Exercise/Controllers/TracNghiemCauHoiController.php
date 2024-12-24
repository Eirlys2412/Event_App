<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Exercise\Models\TracNghiemLoai;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Exercise\Models\TracNghiemCauhoi;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\Resource;

use Illuminate\Support\Facades\Auth; 

class TracNghiemCauHoiController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index(){
        $func = "tracnghiemcauhoi_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="tracnghiemcauhoi_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách câu hỏi trắc nghiệm</li>';  

        $tracnghiemcauhoi = TracNghiemCauhoi::with(['user','hocphan','loaicauhoi'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Exercise::tracnghiemcauhoi.index', compact('tracnghiemcauhoi','breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $tracnghiemloai = TracNghiemLoai::all();
        $hocphan = HocPhan::all();
        $user = User::all();
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi trắc nghiệm</li>';
        $active_menu = "tracnghiemcauhoi_add";
        return view('Exercise::tracnghiemcauhoi.create', compact('tags','tracnghiemloai','hocphan','user','breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'content' => 'required|string',
            'hocphan_id' => 'required|integer', // Thay đổi thành integer nếu hocphan_id là số
            'tags' => 'nullable|string|max:255', // Cho phép null
            // 'resource' => 'nullable|string|max:255', // Cho phép null
            'loai_id' => 'required|integer', // Thay đổi thành integer nếu loai_id là số
            'user_id' => 'required|integer', // Thay đổi thành integer nếu user_id là số
            'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        ]);

        $tag_ids = $request->tag_ids;
        // Lấy tất cả dữ liệu từ yêu cầu
        $requestData = $request->all();

        // Lưu dữ liệu vào cơ sở dữ liệu
        $tracnghiemcauhoi = TracNghiemCauhoi::create($requestData);

        $tagservice = new \App\Http\Controllers\TagController();
        $tagservice->store_tracnghiemcauhoi_tag($tracnghiemcauhoi->id,$tag_ids);

        $resourceIds = [];
        foreach ($request->file('document') as $file) {
            $resourceIds[] = Resource::createResource($request, $file, 'CauHoiTracNghiem')->id;
        }

        $tracnghiemcauhoi->resources = json_encode([
            'tracnghiem_id' => $tracnghiemcauhoi->id,
            'resource_ids' => $resourceIds,
        ]);
        $tracnghiemcauhoi->save();

        // return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Tạo học phần thành công.');
        if($tracnghiemcauhoi){
            return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Tạo học phần thành công.');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }

    public function destroy($id)
    {
        $tracnghiemcauhoi = TracNghiemCauhoi::findOrFail($id);
        $tracnghiemcauhoi->delete();
        return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function edit($id){
        $tracnghiemcauhoi = TracNghiemCauhoi::findOrFail($id);
        $tracnghiemloai = TracNghiemLoai::all();
        $hocphan = HocPhan::all();
        $user = User::all();

        $tags  = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids =DB::select("select tag_id from tag_tracnghiemcauhois where tracnghiemcauhoi_id = ".$tracnghiemcauhoi->id)  ;

        $resourceIds = json_decode($tracnghiemcauhoi->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa câu h��i trắc nghiệm</li>';
        $active_menu = "tracnghiemcauhoi_edit";
        return view('Exercise::tracnghiemcauhoi.edit', compact('resources','tag_ids','tags','tracnghiemcauhoi','tracnghiemloai','hocphan','user','breadcrumb', 'active_menu'));
        // return back()->with('error','Không tìm thấy dữ liệu');
    }

    public function update(Request $request, $id){
        $tracnghiemcauhoi = TracNghiemCauhoi::find($id);

        // Xác thực dữ liệu nhập vào
        $request->validate([
            'content' => 'required|string',
            'hocphan_id' => 'required|integer', // Thay đ��i thành integer nếu hocphan_id là số
            'tags' => 'nullable|string|max:255', // Cho phép null
            'resource' => 'nullable|string|max:255', // Cho phép null
            'loai_id' => 'required|integer', // Thay đ��i thành integer nếu loai_id là số
            'user_id' => 'required|integer', // Thay đ��i thành integer nếu user_id là số
            'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        ]);

        // Lấy dữ liệu từ yêu cầu
        $requestData = $request->all();

        // Tìm câu h��i cần sửa
        $existingResources = json_decode($tracnghiemcauhoi->resources, true) ?? [];
        $existingResourceIds = $existingResources['resource_ids'] ?? [];
        $newResourceIds = [];
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                // Check if file already exists in resources
                $existingResource = Resource::where('file_name', $file->getClientOriginalName())->first();
                if ($existingResource) {
                    // Skip if already linked
                    if (!in_array($existingResource->id, $existingResourceIds)) {
                        $newResourceIds[] = $existingResource->id;
                    }
                } else {
                    // Add new resource
                    $resource = Resource::createResource($request, $file, 'CauHoiTracNghiem');
                    $newResourceIds[] = $resource->id;
                }
            }
        }

        $finalResourceIds = array_unique(array_merge($existingResourceIds, $newResourceIds));

        // Cập nhật dữ liệu vào cơ sở dữ liệu
        $tracnghiemcauhoi->update($requestData);

        $tagservice = new \App\Http\Controllers\TagController();
        $tag_ids = $request->tag_ids;
        $tagservice->update_tracnghiemcauhoi_tag($tracnghiemcauhoi->id,$tag_ids);

        // Save updated resources
        $tracnghiemcauhoi->resources = json_encode([
            'tracnghiem_id' => $tracnghiemcauhoi->id,
            'resource_ids' => $finalResourceIds,
        ]);
        $tracnghiemcauhoi->save();

        return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Sửa câu h��i trắc nghiệm thành công.');
    }

    public function removeResource(Request $request, $tracnghiemcauhoiId, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        if (file_exists(public_path($resource->url))) {
            unlink(public_path($resource->url));
        }
        $resource->delete();

        return response()->json(['success' => true]);
    }


}
