<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_3\Models\EnrollResult;
use App\Modules\Teaching_2\Models\HinhThucThi;
use App\Modules\Exercise\Models\BodeTracNghiem; 
use App\Modules\Exercise\Models\TuLuanCauHoi; 
use App\Modules\Exercise\Models\BoDeTuLuan; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Modules\Exercise\Models\TracNghiemCauhoi;
use Illuminate\Http\Request;


class EnrollResultController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $active_menu = "enroll_results_list";
        $breadcrumb = '<li class="breadcrumb-item"><a href="#">/</a></li>
                       <li class="breadcrumb-item active" aria-current="page">Danh sách kết quả khoá học</li>';

        $enrollResult = EnrollResult::orderBy('id', 'DESC')->paginate($this->pagesize);
        $enrollResult->getCollection()->transform(function ($item) {
        return $item;
    });
        $hocthucthi = HinhThucThi::pluck('title', 'id')->toArray();
        $userList = User::pluck('full_name', 'id')->toArray();

        return view('Teaching_3::enroll_results.index', compact('enrollResult', 'breadcrumb', 'active_menu', 'hocthucthi', 'userList'));
    }

    public function create()
    {
        $active_menu = 'enroll_results_add';
        $enrollment = Enrollment::all(); 
        $hinhthucthi = HinhThucThi::all();
        $users = User::all();
        $boDeTracNghiem = BoDeTracNghiem::all();
        $boDeTuLuan = BoDeTuLuan::all();
        $cauHoiTracNghiem =TracNghiemCauhoi::all();
        $cauHoiTuLuan =TuLuanCauHoi::all();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi tự luận</li>';

    return view('Teaching_3::enroll_results.create', compact('enrollment','hinhthucthi','boDeTracNghiem','boDeTuLuan', 'cauHoiTracNghiem', 'cauHoiTuLuan','users','breadcrumb','active_menu'));
    }

    public function fetchQuestions(Request $request)
{
    $hinhThucThi = $request->hinhthucthi_id;
    $bodeId = $request->bode_id;

    $questions = [];

    if ($hinhThucThi == 1) { // Trắc nghiệm
        $boDe = BoDeTracNghiem::findOrFail($bodeId);
    } elseif ($hinhThucThi == 2) { // Tự luận
        $boDe = BoDeTuLuan::findOrFail($bodeId);
    } else {
        return response()->json(['error' => 'Hình thức thi không hợp lệ'], 400);
    }

    // Parse JSON từ trường `questions`
    $questions = json_decode($boDe->questions, true);

    return response()->json($questions);
}
public function store(Request $request)
{
    // Validate dữ liệu đầu vào
    $validatedData = $request->validate([
        'enroll_id' => 'required|exists:enrollments,id',
        'user_id' => 'required|exists:users,id',
        'hinhthucthi_id' => 'required|exists:hinh_thuc_this,id',
        'bode_id' => 'required',
        'grade' => 'nullable|numeric|min:0|max:100',
    ]);

    // Lấy danh sách câu hỏi từ bộ đề
    $questions = [];
    $hinhThucThi = $validatedData['hinhthucthi_id'];

    if ($hinhThucThi == 1) { // Trắc nghiệm
        $boDe = BoDeTracNghiem::findOrFail($validatedData['bode_id']);
    } elseif ($hinhThucThi == 2) { // Tự luận
        $boDe = BoDeTuLuan::findOrFail($validatedData['bode_id']);
    } else {
        return back()->withErrors(['bode_id' => 'Bộ đề không hợp lệ cho hình thức thi đã chọn.']);
    }

    // Parse danh sách câu hỏi từ trường `questions` của bộ đề
    $parsedQuestions = json_decode($boDe->questions, true); // Parse JSON string từ `questions`

    if (is_array($parsedQuestions)) {
        foreach ($parsedQuestions as $question) {
            $content = null;

            // Lấy nội dung câu hỏi dựa trên hình thức thi
            if ($hinhThucThi == 1) { // Trắc nghiệm
                $questionData = DB::table('trac_nghiem_cauhois')
                    ->where('id', $question['id_question'])
                    ->select('content')
                    ->first();
            } elseif ($hinhThucThi == 2) { // Tự luận
                $questionData = DB::table('tu_luan_cauhois')
                    ->where('id', $question['id_question'])
                    ->select('content')
                    ->first();
            }

            // Kiểm tra nếu tìm thấy nội dung câu hỏi
            if ($questionData) {
                $content = $questionData->content;
            } else {
                $content = 'Không tìm thấy nội dung'; // Fallback nếu không có câu hỏi phù hợp
            }

            $questions[] = [
                'id_question' => $question['id_question'], // ID câu hỏi
                'content' => $content,                    // Nội dung câu hỏi
                'points' => $question['points'],          // Điểm của câu hỏi
                'resouce' => [],                          // Mặc định danh sách tài nguyên rỗng
            ];
        }
    }

    // Thêm danh sách câu hỏi vào trường `chitiet`
    $validatedData['chitiet'] = json_encode($questions);

    // Tạo bản ghi kết quả
    $enrollResult = EnrollResult::create($validatedData);

    return redirect()->route('admin.enroll_results.index')->with('success', 'Kết quả được tạo thành công.');
}



    // Hiển thị form chỉnh sửa EnrollResult
    public function edit($id)
    {
        $active_menu = 'enroll_result_edit';
        $enrollResult = EnrollResult::findOrFail($id);
        $enrollment = Enrollment::all();
        $hinhthucthi = HinhThucThi::all();
        $users = User::all();
        $boDeTracNghiem = BoDeTracNghiem::all();
        $boDeTuLuan = BoDeTuLuan::all();
        $cauHoiTracNghiem =TracNghiemCauhoi::all();
        $cauHoiTuLuan =TuLuanCauHoi::all();
        $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa kết quả enroll</li>';
    
        // Decode danh sách câu hỏi từ JSON để hiển thị trong form chỉnh sửa
        $selectedQuestions = json_decode($enrollResult->chitiet, true) ?? [];
    
        return view('Teaching_3::enroll_results.edit', compact(
            'enrollResult',
            'enrollment',
            'hinhthucthi',
            'users',
            'boDeTracNghiem',
            'boDeTuLuan',
            'cauHoiTracNghiem',
            'cauHoiTuLuan',
            'tags',
            'breadcrumb',
            'active_menu',
            'selectedQuestions'
        ));
    }
    
    
    public function update(Request $request, $id)
{
    try {
        $enrollResult = EnrollResult::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'enroll_id' => 'required|exists:enrollments,id',
            'user_id' => 'required|exists:users,id',
            'hinhthucthi_id' => 'required|exists:hinh_thuc_this,id',
            'bode_id' => 'required',
            'grade' => 'nullable|numeric|min:0|max:100',
            'selected_questions' => 'nullable|array', // Mảng các câu hỏi được chọn
            'points' => 'nullable|array', // Mảng điểm của các câu hỏi
        ]);

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

        $validatedData['chitiet'] = json_encode($questions);

        // Cập nhật dữ liệu vào DB
        $enrollResult->update($validatedData);

        // Xử lý liên kết tag (nếu cần)
        $tag_ids = $request->tag_ids;
        if (!empty($tag_ids)) {
            $tagservice = new \App\Http\Controllers\TagController();
            $tagservice->store_enrollResult_tag($enrollResult->id, $tag_ids);
        }

        // Redirect với thông báo thành công
        return redirect()->route('admin.enroll_results.index')->with('success', 'Kết quả được cập nhật thành công.');
    } catch (\Exception $e) {
        // Log lỗi chi tiết
        Log::error('Error updating enrollResult:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Redirect với thông báo lỗi
        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu.');
    }
}

    


    // Delete a enrollResult record
    public function destroy($id)
    {
        $enrollResult = enrollResult::findOrFail($id);
        $enrollResult->delete();

        return redirect()->route('admin.enroll_results.index')->with('success', 'Bộ đề tự luận đã được xóa thành công.');
    }
}
