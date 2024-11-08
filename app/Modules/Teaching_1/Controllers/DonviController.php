<?php

namespace App\Modules\Teaching_1\Controllers;

use Illuminate\Http\Request;

class DonviController extends Controller
{
    public function index()
    {
        // Sử dụng Model để lấy dữ liệu
        $donVis = Donvi::all();
        return response()->json($donVis);
        // hoặc
        // return view('donvi.index', compact('donvis'));
    }
}
