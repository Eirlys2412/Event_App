<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Exercise\Models\TuLuanCauHoi;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\DB;
use App\Modules\Resource\Models\Resource;

use Illuminate\Support\Facades\Auth; 

class TuLuanCauHoiController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index(){
        $func = "tuluancauhoi_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        $active_menu="tuluancauhoi_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách câu hỏi tự luận</li>';  

        $tuluancauhoi = TuLuanCauHoi::with(['user','hocphan'])->orderBy('id', 'DESC')->paginate($this->pagesize);
        return view('Exercise::tuluancauhoi.index', compact('tuluancauhoi','breadcrumb', 'active_menu'));

    }

    public function create()
    {
        $hocphan = HocPhan::all();
        $user = User::all();
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_add";
        return view('Exercise::tuluancauhoi.create', compact('tags','hocphan','user','breadcrumb', 'active_menu'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu nhập vào
        $request->validate([
            'content' => 'required|string',
            'hocphan_id' => 'required|integer', // Thay đổi thành integer nếu hocphan_id là số
            'tags' => 'nullable|string|max:255', // Cho phép null
            'user_id' => 'required|integer', // Thay đổi thành integer nếu user_id là số
            'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        ]);

        $tag_ids = $request->tag_ids;
        // Lấy tất cả dữ liệu từ yêu cầu
        $requestData = $request->all();

        // $helpController = new \App\Http\Controllers\FilesController();
        // $requestData['content'] = $helpController->store($request->file('content'));

        // Lưu dữ liệu vào cơ sở dữ liệu
        $tuluancauhoi = TuLuanCauHoi::create($requestData);

        $tagservice = new \App\Http\Controllers\TagController();
        $tagservice->store_tuluancauhoi_tag($tuluancauhoi->id,$tag_ids);

        $resourceIds = [];
        foreach ($request->file('document') as $file) {
            $resourceIds[] = Resource::createResource($request, $file, 'CauHoiTuLuan')->id;
        }

        $tuluancauhoi->resources = json_encode([
            'tuluan_id' => $tuluancauhoi->id,
            'resource_ids' => $resourceIds,
        ]);
        $tuluancauhoi->save();

        if($tuluancauhoi){
            return redirect()->route('admin.tuluancauhoi.index')->with('thongbao', 'Tạo câu hỏi thành công.');
        }
        else
        {
            return back()->with('error','Có lỗi xãy ra!');
        }    
    }

    public function destroy($id)
    {
        $tuluancauhoi = TuLuanCauHoi::findOrFail($id);
        $tuluancauhoi->delete();
        return redirect()->route('admin.tuluancauhoi.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function edit($id){
        $tuluancauhoi = TuLuanCauHoi::findOrFail($id);
        $hocphan = HocPhan::all();
        $user = User::all();

        $tags  = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids =DB::select("select tag_id from tag_tuluancauhois where tuluancauhoi_id = ".$tuluancauhoi->id)  ;

        $resourceIds = json_decode($tuluancauhoi->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa câu hỏi tự luận</li>';
        $active_menu = "tuluancauhoi_edit";
        return view('Exercise::tuluancauhoi.edit', compact('resources','tag_ids','tags','tuluancauhoi','hocphan','user','breadcrumb', 'active_menu'));
    }

    public function removeResource(Request $request, $tuluancauhoiId, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        if (file_exists(public_path($resource->url))) {
            unlink(public_path($resource->url));
        }
        $resource->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id){
        $tuluancauhoi = TuLuanCauHoi::find($id);

       // Xác thực dữ liệu nhập vào
       $request->validate([
        'content' => 'required|string',
        'hocphan_id' => 'required|integer', // Thay đổi thành integer nếu hocphan_id là số
        'tags' => 'nullable|string|max:255', // Cho phép null
        'user_id' => 'required|integer', // Thay đổi thành integer nếu user_id là số
        'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        ]);

        // Lấy dữ liệu từ yêu cầu
        $requestData = $request->all();

        // Tìm câu h��i cần sửa
        $existingResources = json_decode($tuluancauhoi->resources, true) ?? [];
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
                    $resource = Resource::createResource($request, $file, 'CauHoiTuLuan');
                    $newResourceIds[] = $resource->id;
                }
            }
        }

        $finalResourceIds = array_unique(array_merge($existingResourceIds, $newResourceIds));

        // $helpController = new \App\Http\Controllers\FilesController();
        // $requestData['content'] = $helpController->store($request->file('content'));

        // Cập nhật dữ liệu vào cơ sở dữ liệu
        $tuluancauhoi->update($requestData);

        $tagservice = new \App\Http\Controllers\TagController();
        $tag_ids = $request->tag_ids;
        $tagservice->update_tuluancauhoi_tag($tuluancauhoi->id,$tag_ids);

        // Save updated resources
        $tuluancauhoi->resources = json_encode([
            'tuluan_id' => $tuluancauhoi->id,
            'resource_ids' => $finalResourceIds,
        ]);
        $tuluancauhoi->save();

        return redirect()->route('admin.tuluancauhoi.index')->with('thongbao', 'Sửa câu h��i trắc nghiệm thành công.');
    }
}
