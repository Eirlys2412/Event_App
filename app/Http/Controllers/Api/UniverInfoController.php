<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UniverInfoController extends Controller
{
    //
    public function getNganhs() 
{
    try {
        // Lấy tất cả dữ liệu từ bảng `nganhs`
        $nganhs = \App\Modules\Teaching_1\Models\Nganh::all();

        return response()->json([
            'success' => true,
            'data' => $nganhs,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy danh sách ngành: ' . $e->getMessage(),
        ], 500);
    }
}

public function getDonVis() 
{
    try {
        // Lấy tất cả dữ liệu từ bảng `donvi`
        $donVis = \App\Modules\Teaching_1\Models\Donvi::all();

        return response()->json([
            'success' => true,
            'data' => $donVis,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy danh sách đơn vị: ' . $e->getMessage(),
        ], 500);
    }
}

public function chuyenNganhs() 
{
    try {
        // Lấy tất cả dữ liệu từ bảng `chuyennganhs`
        $chuyenNganhs = \App\Modules\Teaching_1\Models\ChuyenNganh::all();

        return response()->json([
            'success' => true,
            'data' => $chuyenNganhs,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy danh sách chuyên ngành: ' . $e->getMessage(),
        ], 500);
    }
}

}
