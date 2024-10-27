<?php

namespace App\Modules\Motion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    use HasFactory;

    protected $table = 'motion'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'title', // Tiêu đề cảm xúc
        'icon',   // Biểu tượng cảm xúc
    ];

    public function items()
    {
        return $this->hasMany(MotionItem::class, 'id_motion'); // Quan hệ một-nhiều với MotionItem
    }
}