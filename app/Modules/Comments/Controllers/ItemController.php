<?php

namespace App\Modules\Comments\Controllers;

use App\Modules\Comments\Models\Comment; // Kiểm tra xem Model Comment đã được import đúng
use App\Modules\Comments\Models\Item;    // Kiểm tra xem Model Item đã được import đúng
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Đảm bảo import lớp Controller đúng cách
class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id); // Tìm item theo ID
        return view('items.show', compact('item')); // Trả về view với thông tin item
    }
}