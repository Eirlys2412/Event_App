<?php

namespace App\Modules\Motion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotionItem extends Model
{
    use HasFactory;

    protected $table = 'motionitem'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'id_motion', // Khóa ngoại tham chiếu đến Motion
        'item_code', // Mã định danh cho từng mục
        'count',     // Số lượng sử dụng
    ];

    public function motion()
    {
        return $this->belongsTo(Motion::class, 'id_motion'); // Quan hệ nhiều-một với Motion
    }
}