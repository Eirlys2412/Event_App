<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetCodeMail;
use Illuminate\Contracts\Mail\Mailable;

class PasswordRecoveryController extends Controller
{
    //
    public function sendResetCode(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    // Tạo mã xác nhận
    $code = rand(100000, 999999);

    // Lưu mã xác nhận vào Cache với thời gian hết hạn 5 phút
    Cache::put('password_reset_' . $request->email, $code, 300);

    // Gửi email với mã xác nhận
    Mail::to($request->email)->send(new ResetCodeMail($code));

    return response()->json(['message' => 'Mã xác nhận đã được gửi đến email.']);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    // Kiểm tra mã xác nhận
    $cachedCode = Cache::get('password_reset_' . $request->email);
    if (!$cachedCode || $cachedCode != $request->code) {
        return response()->json(['message' => 'Mã xác nhận không hợp lệ hoặc đã hết hạn.'], 400);
    }

    // Đặt lại mật khẩu
    $user = User::where('email', $request->email)->first();
    $user->password = bcrypt($request->password);
    $user->save();

    // Xóa mã xác nhận
    Cache::forget('password_reset_' . $request->email);

    return response()->json(['message' => 'Mật khẩu đã được cập nhật thành công.']);
}
}
