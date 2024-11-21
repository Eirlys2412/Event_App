<?php

namespace App\Http\Controllers;

use App\Modules\Book\Models\BookPoint;
use App\Modules\Book\Models\BookTransaction;
use App\Modules\Book\Models\BookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookTransactionController extends Controller
{
    /**
     * Xử lý giao dịch sách.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processTransaction(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
            'transaction_type' => 'required|string',
        ]);

        // Lấy điểm tương ứng với hành động từ bảng BookPoint
        $bookPoint = BookPoint::where('func_cmd', $request->transaction_type)->first();

        if (!$bookPoint) {
            return response()->json(['error' => 'Loại giao dịch không hợp lệ.'], 400);
        }

        $pointsChange = $bookPoint->point;

        DB::beginTransaction();
        try {
            // Tìm hoặc tạo người dùng trong BookUser
            $bookUser = BookUser::firstOrCreate(['user_id' => $request->user_id]);

            // Kiểm tra nếu điểm không đủ (nếu trừ điểm)
            if ($pointsChange < 0 && $bookUser->points < abs($pointsChange)) {
                return response()->json(['error' => 'Người dùng không đủ điểm.'], 400);
            }

            // Cập nhật điểm cho người dùng
            $bookUser->points += $pointsChange;
            $bookUser->save();

            // Ghi lại giao dịch
            BookTransaction::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'transaction_type' => $request->transaction_type,
                'points_change' => $pointsChange,
            ]);

            DB::commit();

            return response()->json(['success' => 'Giao dịch thành công.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Lỗi xử lý giao dịch: ' . $e->getMessage()], 500);
        }
    }
}
