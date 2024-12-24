<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Modules\Exercise\Models\TracNghiemLoai;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Exercise\Models\TracNghiemCauhoi;
use App\Modules\Exercise\Models\TracNghiemDapan;
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
    // Xác thực dữ liệu đầu vào
    // dd($request->all());
    $request->validate([
        'content' => 'required|string',
        'hocphan_id' => 'required|integer',
        'tags' => 'nullable|string|max:255',
        'loai_id' => 'required|integer',
        'user_id' => 'required|integer',
        'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        'answers' => 'required|array',
        'answers.*.content' => 'required|string',
        'answers.*.is_correct' => 'required|in:0,1', // Chỉ chấp nhận giá trị 0 hoặc 1
    ]);
    
    

    try {
        // Tạo câu hỏi mới
        $tracnghiemcauhoi = TracNghiemCauhoi::create([
            'content' => $request->input('content'),
            'hocphan_id' => $request->input('hocphan_id'),
            'tags' => $request->input('tags'),
            'loai_id' => $request->input('loai_id'),
            'user_id' => $request->input('user_id'),
        ]);

        // Gắn thẻ vào câu hỏi
        $tag_ids = $request->tag_ids;
        $tagservice = new \App\Http\Controllers\TagController();
        $tagservice->store_tracnghiemcauhoi_tag($tracnghiemcauhoi->id, $tag_ids);

        // Xử lý tài nguyên của câu hỏi
        $resourceIds = [];
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                $resourceIds[] = Resource::createResource($request, $file, 'CauHoiTracNghiem')->id;
            }
        }
        $tracnghiemcauhoi->resources = json_encode([
            'tracnghiem_id' => $tracnghiemcauhoi->id,
            'resource_ids' => $resourceIds,
        ]);
        $tracnghiemcauhoi->save();

        $i =0;
        for ($i = 0; $i < count($request->input('answers')); $i++) {
            TracNghiemDapan::create([
                'tracnghiem_id' => $tracnghiemcauhoi->id,
                'content' => $request->input("answers.$i.content"),
                'resounce_list' => null,
                'is_correct' => $request->input("answers.$i.is_correct"),
            ]);
        }
        
        return redirect()->route('admin.tracnghiemcauhoi.index')->with('success', 'Tạo câu hỏi và đáp án thành công.');
    } catch (\Exception $e) {
        Log::error('Error creating TracNghiemCauHoi:', ['message' => $e->getMessage()]);
        return back()->with('error', 'Đã xảy ra lỗi khi tạo câu hỏi và đáp án.');
    }
}


    public function destroy($id)
    {
        $tracnghiemcauhoi = TracNghiemCauhoi::findOrFail($id);
        $tracnghiemcauhoi->delete();
        return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Xóa học phần thành công.');
    }

    public function edit($id)
    {
        $tracnghiemcauhoi = TracNghiemCauhoi::with('answers')->findOrFail($id); // Eager load 'answers'
        $tracnghiemloai = TracNghiemLoai::all();
        $hocphan = HocPhan::all();
        $user = User::all();
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $tag_ids = DB::table('tag_tracnghiemcauhois')->where('tracnghiemcauhoi_id', $tracnghiemcauhoi->id)->pluck('tag_id')->toArray();
    
        // Lấy các tài nguyên đã liên kết với câu hỏi
        $resourceIds = json_decode($tracnghiemcauhoi->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();
    
        // Xây dựng breadcrumb và menu
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sửa câu hỏi trắc nghiệm</li>';
        $active_menu = "tracnghiemcauhoi_edit";
    
        return view('Exercise::tracnghiemcauhoi.edit', compact('resources', 'tag_ids', 'tags', 'tracnghiemcauhoi', 'tracnghiemloai', 'hocphan', 'user', 'breadcrumb', 'active_menu'));
    }
    


public function update(Request $request, $id)
{
    $tracnghiemcauhoi = TracNghiemCauhoi::findOrFail($id); // Find the question by ID

    // Validate the input data
    $request->validate([
        'content' => 'required|string',
        'hocphan_id' => 'required|integer',
        'tags' => 'nullable|string|max:255',
        'loai_id' => 'required|integer',
        'user_id' => 'required|integer',
        'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
        'answers' => 'required|array', // Answers must be an array
        'answers.*.content' => 'required|string', // Each answer must have content
        'answers.*.is_correct' => 'required|in:0,1', // Each answer must be marked as correct (0 or 1)
    ]);

    // Get the input data
    $requestData = $request->all();

    // Handle existing resources (files)
    $existingResources = json_decode($tracnghiemcauhoi->resources, true) ?? [];
    $existingResourceIds = $existingResources['resource_ids'] ?? [];
    $newResourceIds = [];

    if ($request->hasFile('document')) {
        foreach ($request->file('document') as $file) {
            // Check if the resource already exists
            $existingResource = Resource::where('file_name', $file->getClientOriginalName())->first();
            if ($existingResource) {
                // If the resource exists, skip adding it again
                if (!in_array($existingResource->id, $existingResourceIds)) {
                    $newResourceIds[] = $existingResource->id;
                }
            } else {
                // If the resource doesn't exist, create a new one
                $resource = Resource::createResource($request, $file, 'CauHoiTracNghiem');
                $newResourceIds[] = $resource->id;
            }
        }
    }

    // Combine the existing and new resource IDs
    $finalResourceIds = array_unique(array_merge($existingResourceIds, $newResourceIds));

    // Update the question with the new data
    $tracnghiemcauhoi->update($requestData);

    // Update tags
    $tagservice = new \App\Http\Controllers\TagController();
    $tag_ids = $request->tag_ids;
    $tagservice->update_tracnghiemcauhoi_tag($tracnghiemcauhoi->id, $tag_ids);

    // Update resources for the question
    $tracnghiemcauhoi->resources = json_encode([
        'tracnghiem_id' => $tracnghiemcauhoi->id,
        'resource_ids' => $finalResourceIds,
    ]);
    $tracnghiemcauhoi->save();

    // Now update the answers
    // First, delete the old answers
    TracNghiemDapan::where('tracnghiem_id', $tracnghiemcauhoi->id)->delete();

    // Add the new answers
    foreach ($request->input('answers') as $answer) {
        TracNghiemDapan::create([
            'tracnghiem_id' => $tracnghiemcauhoi->id,
            'content' => $answer['content'],
            'is_correct' => $answer['is_correct'],
            'resounce_list' => null, // Handle the resources for the answers if needed
        ]);
    }

    return redirect()->route('admin.tracnghiemcauhoi.index')->with('thongbao', 'Sửa câu hỏi trắc nghiệm thành công.');
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
