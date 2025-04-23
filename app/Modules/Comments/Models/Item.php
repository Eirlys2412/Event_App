<?php

namespace App\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'code', // Đảm bảo thuộc tính này có trong mảng fillable
    ];

    // Phương thức để truy cập item code
    public function getCode()
    {
        return $this->code; // Trả về giá trị của thuộc tính code
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}


