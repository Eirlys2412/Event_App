<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Exercise\Models\BodeTracNghiem;
use App\Modules\Exercise\Models\TracNghiemCauhoi;
use App\Modules\Exercise\Models\TracNghiemDapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ExerciseController extends Controller
{
    // Tạo câu hỏi trắc nghiệm
    public function storeQuestion(Request $request) {
        $request->validate([
            'content' => 'required|string',
            'hocphan_id' => 'required|integer|exists:hoc_phans,id',
            'resources' => 'nullable|array',  // Kiểm tra resources là một mảng
            'resources.tracnghiem_id' => 'required_with:resources|integer',
            'resources.resource_ids' => 'required_with:resources|array',
            'resources.resource_ids.*' => 'integer', // Kiểm tra từng phần tử trong mảng
            'loai_id' => 'required|integer',
        ]);

        // Chuyển `resources` thành JSON nếu có
        $resourcesJson = $request->has('resources') ? json_encode($request->resources) : null;

        $question = TracNghiemCauhoi::create([
            'content' => $request->content,
            'hocphan_id' => $request->hocphan_id,
            'resources' => $resourcesJson,
            'loai_id' => $request->loai_id,
            'user_id' =>  $request->user_id
        ]);

        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Không thể tạo câu hỏi'], 500);
        }

        return response()->json(['success' => true, 'data' => $question]);
    }

    // Tạo đáp án
    public function storeAnswer(Request $request) {
        $request->validate([
            'tracnghiem_id' => 'required|integer|exists:trac_nghiem_cauhois,id',
            'content' => 'required|string',
            'resounce_list' => 'nullable|string',
            'is_correct' => 'required|boolean',
        ]);

        $answer = TracNghiemDapan::create([
            'tracnghiem_id' => $request->tracnghiem_id,
            'content' => $request->content,
            'resounce_list' => $request->resounce_list,
            'is_correct' => $request->is_correct,
        ]);

        if (!$answer) {
            return response()->json(['success' => false, 'message' => 'Không thể tạo đáp án'], 500);
        }

        return response()->json(['success' => true, 'data' => $answer]);
    }

    // Tạo đề thi trắc nghiệm
    public function storeQuiz(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'hocphan_id' => 'required|integer|exists:hoc_phans,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'time' => 'required|integer',
            'tags' => 'nullable|string',
            'total_points' => 'required|integer',
            'questions' => 'required|array', // Kiểm tra questions là mảng
            'questions.*.id_question' => 'required|integer|exists:trac_nghiem_cauhois,id',
            'questions.*.points' => 'required|integer',
        ]);

        $quiz = BodeTracNghiem::create([
            'title' => $request->title,
            'hocphan_id' => $request->hocphan_id,
            'slug' => Str::slug($request->title),
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => Carbon::parse($request->end_time),
            'time' => $request->time,
            'tags' => $request->tags,
            'user_id' =>   $request->user_id,
            'total_points' => $request->total_points,
            'questions' => json_encode($request->questions), // Chuyển thành JSON
        ]);

        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Không thể tạo đề thi'], 500);
        }

        return response()->json(['success' => true, 'data' => $quiz]);
    }
}
