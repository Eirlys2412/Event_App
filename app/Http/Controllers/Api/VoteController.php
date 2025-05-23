<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\TuongTac\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\UserPage\Models\UserPage;
use App\Models\User;


class VoteController extends Controller
{
    public function vote(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'item_code' => 'required|string', // Tên model: App\Models\Blog, App\Models\Event, App\Models\User
            'point' => 'required|integer|min:1|max:5',
        ]);
    
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'msg' => 'Bạn cần đăng nhập!']);
        }

        $itemId = $request->item_id;
        $itemCode = $request->item_code; // VD: 'App\Models\Blog', 'App\Models\Event', 'App\Models\User'
        $point = $request->point;

        // Cộng điểm cho user
        User::add_points($userId, 1);

        // Tạo hoặc cập nhật vote
        $vote = Vote::updateOrCreate(
            [
                'user_id' => $userId,
                'votable_id' => $itemId,
                'votable_type' => $itemCode
            ],
            [
                'rating' => $point
            ]
        );

        // Tính điểm trung bình và số lượng vote cho đối tượng này
        $averagePoint = Vote::where('votable_id', $itemId)
            ->where('votable_type', $itemCode)
            ->avg('rating');
        $count = Vote::where('votable_id', $itemId)
            ->where('votable_type', $itemCode)
            ->count();

        return response()->json([
            'success' => true,
            'averagePoint' => round($averagePoint, 2),
            'count' => $count
        ]);
    }

    public function like(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'item_code' => 'required|string',
        ]);
    
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'msg' => 'Bạn cần đăng nhập!']);
        }

        $itemId = $request->item_id;
        $itemCode = $request->item_code;

        // Kiểm tra xem user đã like chưa
        $existingLike = Vote::where([
            'user_id' => $userId,
            'votable_id' => $itemId,
            'votable_type' => $itemCode,
            'rating' => 1
        ])->first();

        if ($existingLike) {
            // Nếu đã like thì unlike (xóa)
            $existingLike->delete();
            $isLiked = false;
        } else {
            // Nếu chưa like thì tạo mới
            Vote::create([
                'user_id' => $userId,
                'votable_id' => $itemId,
                'votable_type' => $itemCode,
                'rating' => 1
            ]);
            $isLiked = true;
        }

        // Đếm tổng số like
        $totalLikes = Vote::where([
            'votable_id' => $itemId,
            'votable_type' => $itemCode,
            'rating' => 1
        ])->count();

        return response()->json([
            'success' => true,
            'isLiked' => $isLiked,
            'totalLikes' => $totalLikes
        ]);
    }
}
