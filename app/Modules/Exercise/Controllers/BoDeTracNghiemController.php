<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Modules\Exercise\Models\BodeTracNghiem; 
use App\Modules\Teaching_2\Models\HocPhan;
use App\Models\User;
use App\Modules\Exercise\Models\TracNghiemCauhoi;

class BoDeTracNghiemController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    // List all BoDeTracNghiem records
    public function index()
    {
        $active_menu = "bode_tracnghiem_list";
        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li>
                       <li class="breadcrumb-item active" aria-current="page">Danh sách bộ đề trắc nghiệm</li>';

        $bodeTracNghiem = BoDeTracNghiem::orderBy('id', 'DESC')->paginate($this->pagesize);
        // Tính số lượng câu hỏi từ JSON
        $bodeTracNghiem->getCollection()->transform(function ($item) {
        $questions = json_decode($item->questions, true);
        $item->so_cau_hoi = is_array($questions) ? count($questions) : 0; // Đếm số lượng phần tử
        return $item;
    });
        $hocPhanList = HocPhan::pluck('title', 'id')->toArray();
        $userList = User::pluck('full_name', 'id')->toArray();

        return view('Exercise::bode_tracnghiem.index', compact('bodeTracNghiem', 'breadcrumb', 'active_menu', 'hocPhanList', 'userList'));
    }

    // Show the form for creating a new BoDeTracNghiem
    public function create()
    {
        $active_menu = 'bode_tracnghiem_add';
        $cauHois = TracNghiemCauHoi::all(); // Lấy tất cả câu hỏi
        $hocphan = HocPhan::all();
        $users = User::all();
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi trắc nghiệm</li>';

    return view('Exercise::bode_tracnghiem.create', compact('cauHois','hocphan', 'users','tags','breadcrumb','active_menu'));
    }

    // Store a new BoDeTracNghiem record
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'hocphan_id' => 'required|exists:hoc_phans,id',
            'slug' => 'nullable|string|max:255|unique:bode_tracnghiem,slug',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'time' => 'required|integer|min:1',
            'tags' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'total_points' => 'required|integer|min:0',
            'selected_questions' => 'nullable|array', // Mảng các câu hỏi được chọn
            'points' => 'nullable|array', // Mảng điểm cho các câu hỏi
        ]);
    
        // Xử lý slug tự động nếu không nhập
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }
    
        // Tạo danh sách câu hỏi dưới dạng JSON
        $questions = [];
        $selectedQuestions = $request->input('selected_questions', []);
        $points = $request->input('points', []);
    
        foreach ($selectedQuestions as $questionId) {
            $questions[] = [
                'id_question' => $questionId,
                'points' => $points[$questionId] ?? 0,
            ];
        }
        $validatedData['questions'] = json_encode($questions);
    
        // Tạo bộ đề trắc nghiệm
        $bodeTracNghiem = BoDeTracNghiem::create($validatedData);
    
        // Liên kết tag nếu có
        $tag_ids = $request->tag_ids;
        if (!empty($tag_ids)) {
            $tagservice = new \App\Http\Controllers\TagController();
            $tagservice->store_bodetracnghiem_tag($bodeTracNghiem->id, $tag_ids);
        }
    
        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.bode_tracnghiem.index')->with('success', 'Bộ đề trắc nghiệm được tạo thành công.');
    }
    

    // Show a specific BoDeTracNghiem record
    public function show(BoDeTracNghiem $bode_tracnghiem)
    {
        $active_menu = 'bode_tracnghiem_show';

        $questions = $bode_tracnghiem->tracnghiemCauhois();

        return view('Exercise::bode_tracnghiem.show', compact('bode_tracnghiem', 'active_menu', 'questions'));
    }

    // Hiển thị form chỉnh sửa bộ đề trắc nghiệm
    public function edit($id)
    {
        $active_menu = 'bode_tracnghiem_edit';
        $bodeTracNghiem = BoDeTracNghiem::findOrFail($id);
        $cauHois = TracNghiemCauHoi::all(); // Lấy tất cả câu hỏi
        $hocphan = HocPhan::all();
        $users = User::all();
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa bộ đề trắc nghiệm</li>';
    
        // Decode questions từ JSON để hiển thị trong form chỉnh sửa
        $selectedQuestions = json_decode($bodeTracNghiem->questions, true) ?? [];
    
        return view('Exercise::bode_tracnghiem.edit', compact(
            'bodeTracNghiem',
            'cauHois',
            'hocphan',
            'users',
            'tags',
            'breadcrumb',
            'active_menu',
            'selectedQuestions'
        ));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $bodeTracNghiem = BoDeTracNghiem::findOrFail($id);
    
            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'hocphan_id' => 'required|exists:hoc_phans,id',
                'slug' => 'nullable|string|max:255|unique:bode_tracnghiem,slug,' . $id,
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'time' => 'required|integer|min:1',
                'tags' => 'nullable|string|max:255',
                'user_id' => 'required|exists:users,id',
                'total_points' => 'required|integer|min:0',
                'selected_questions' => 'nullable|array', // Mảng các câu hỏi được chọn
                'points' => 'nullable|array', // Mảng điểm cho các câu hỏi
            ]);
    
            // Xử lý slug tự động nếu không nhập
            if (empty($validatedData['slug'])) {
                $validatedData['slug'] = Str::slug($validatedData['title']);
            }
    
            // Xử lý danh sách câu hỏi
            $questions = [];
            $selectedQuestions = $request->input('selected_questions', []);
            $points = $request->input('points', []);
    
            foreach ($selectedQuestions as $questionId) {
                $questions[] = [
                    'id_question' => $questionId,
                    'points' => $points[$questionId] ?? 0,
                ];
            }
    
            $validatedData['questions'] = json_encode($questions);
    
            // Cập nhật dữ liệu vào DB
            $bodeTracNghiem->update($validatedData);
    
            // Xử lý liên kết tag
            $tag_ids = $request->tag_ids;
            if (!empty($tag_ids)) {
                $tagservice = new \App\Http\Controllers\TagController();
                $tagservice->store_bodetracnghiem_tag($bodeTracNghiem->id, $tag_ids);
            }
    
            // Redirect với thông báo thành công
            return redirect()->route('admin.bode_tracnghiem.index')->with('success', 'Bộ đề trắc nghiệm được cập nhật thành công.');
        } catch (\Exception $e) {
            // Log lỗi chi tiết
            Log::error('Error updating BoDeTracNghiem:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Redirect với thông báo lỗi
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu.');
        }
    }
    


    // Delete a BoDeTracNghiem record
    public function destroy($id)
    {
        $bodeTracNghiem = BoDeTracNghiem::findOrFail($id);
        $bodeTracNghiem->delete();

        return redirect()->route('admin.bode_tracnghiem.index')->with('success', 'Bộ đề trắc nghiệm đã được xóa thành công.');
    }
}
