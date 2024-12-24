<?php

namespace App\Modules\Exercise\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Modules\Exercise\Models\BoDeTuLuan; 
use App\Modules\Teaching_2\Models\HocPhan;
use App\Models\User;
use App\Modules\Exercise\Models\TuLuanCauHoi;

class BoDeTuLuanController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    // List all BoDeTuLuan records
    public function index()
    {
        $active_menu = "bode_tuluans_list";
        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li>
                       <li class="breadcrumb-item active" aria-current="page">Danh sách bộ đề tự luận</li>';

        $bodeTuLuan = BoDeTuLuan::orderBy('id', 'DESC')->paginate($this->pagesize);
        // Tính số lượng câu hỏi từ JSON
        $bodeTuLuan->getCollection()->transform(function ($item) {
        $questions = json_decode($item->questions, true);
        $item->so_cau_hoi = is_array($questions) ? count($questions) : 0; // Đếm số lượng phần tử
        return $item;
    });
        $hocPhanList = HocPhan::pluck('title', 'id')->toArray();
        $userList = User::pluck('full_name', 'id')->toArray();

        return view('Exercise::bode_tuluans.index', compact('bodeTuLuan', 'breadcrumb', 'active_menu', 'hocPhanList', 'userList'));
    }

    // Show the form for creating a new BoDeTuLuan
    public function create()
    {
        $active_menu = 'bode_tuluans_add';
        $cauHois = TuLuanCauHoi::all(); // Lấy tất cả câu hỏi
        $hocphan = HocPhan::all();
        $users = User::all();
        $tags = \App\Models\Tag::where('status','active')->orderBy('title','ASC')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi tự luận</li>';

    return view('Exercise::bode_tuluans.create', compact('cauHois','hocphan', 'users','tags','breadcrumb','active_menu'));
    }

    // Store a new BoDeTuLuan record
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'hocphan_id' => 'required|exists:hoc_phans,id',
            'slug' => 'nullable|string|max:255|unique:bode_tuluans,slug',
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
    
        // Tạo bộ đề tự luận
        $bodeTuLuan = BoDeTuLuan::create($validatedData);
    
        // Liên kết tag nếu có
        $tag_ids = $request->tag_ids;
        if (!empty($tag_ids)) {
            $tagservice = new \App\Http\Controllers\TagController();
            $tagservice->store_bodetuluan_tag($bodeTuLuan->id, $tag_ids);
        }
    
        // Chuyển hướng với thông báo thành công
        return redirect()->route('admin.bode_tuluans.index')->with('success', 'Bộ đề tự luận được tạo thành công.');
    }
    

    // Show a specific BoDeTuLuan record
    public function show(BoDeTuLuan $bode_tuluans)
    {
        $active_menu = 'bode_tuluans_show';

        $questions = $bode_tuluans->tuluanCauhois();

        return view('Exercise::bode_tuluans.show', compact('bode_tuluans', 'active_menu', 'questions'));
    }

    // Hiển thị form chỉnh sửa bộ đề tự luận
    public function edit($id)
    {
        $active_menu = 'bode_tuluans_edit';
        $bodeTuLuan = BoDeTuLuan::findOrFail($id);
        $cauHois = TuLuanCauHoi::all(); // Lấy tất cả câu hỏi
        $hocphan = HocPhan::all();
        $users = User::all();
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa bộ đề tự luận</li>';
    
        // Decode questions từ JSON để hiển thị trong form chỉnh sửa
        $selectedQuestions = json_decode($bodeTuLuan->questions, true) ?? [];
    
        return view('Exercise::bode_tuluans.edit', compact(
            'bodeTuLuan',
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
            $bodeTuLuan = BoDeTuLuan::findOrFail($id);
    
            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'hocphan_id' => 'required|exists:hoc_phans,id',
                'slug' => 'nullable|string|max:255|unique:bode_tuluans,slug,' . $id,
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
            $bodeTuLuan->update($validatedData);
    
            // Xử lý liên kết tag
            $tag_ids = $request->tag_ids;
            if (!empty($tag_ids)) {
                $tagservice = new \App\Http\Controllers\TagController();
                $tagservice->store_bodetuluan_tag($bodeTuLuan->id, $tag_ids);
            }
    
            // Redirect với thông báo thành công
            return redirect()->route('admin.bode_tuluans.index')->with('success', 'Bộ đề tự luận được cập nhật thành công.');
        } catch (\Exception $e) {
            // Log lỗi chi tiết
            Log::error('Error updating BoDeTuLuan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Redirect với thông báo lỗi
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu.');
        }
    }
    


    // Delete a BoDeTuLuan record
    public function destroy($id)
    {
        $bodeTuLuan = BoDeTuLuan::findOrFail($id);
        $bodeTuLuan->delete();

        return redirect()->route('admin.bode_tuluans.index')->with('success', 'Bộ đề tự luận đã được xóa thành công.');
    }
}
