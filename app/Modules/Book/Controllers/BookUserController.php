<?php

namespace App\Modules\Book\Controllers;

use App\Modules\Book\Models\BookUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookUserController extends Controller
{
    // Hiển thị danh sách tất cả người dùng và điểm của họ từ BookUser
    public function index()
    {
        $users = User::paginate(20);
        $breadcrumb = '<li class="breadcrumb-item active" aria-current="page">Danh sách</li>';
        $active_menu = "bookuser_list";
        $bookUsers = $users->map(function ($user) {
            $bookUser = BookUser::where('user_id', $user->id)->first();
            return [
                'user' => $user,
                'points' => $bookUser ? $bookUser->points : 0,
            ];
        });

        return view('Book::bookusers.index', compact('bookUsers', 'users','breadcrumb', 'active_menu'));
    }

    // Cập nhật điểm cho người dùng
    public function updatePoints(Request $request, $userId)
    {
        $request->validate([
            'points' => 'required|integer'
        ]);

        // Tìm BookUser theo user_id
        $bookUser = BookUser::where('user_id', $userId)->first();
        if (!$bookUser) {
            return redirect()->route('admin.bookusers.index')->with('error', 'Không tìm thấy người dùng');
        }

        // Cập nhật điểm
        $bookUser->points = $request->points;
        $bookUser->save();

        return redirect()->route('admin.bookusers.index')->with('success', 'Cập nhật điểm thành công');
    }
}
