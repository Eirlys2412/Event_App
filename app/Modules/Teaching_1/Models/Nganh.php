<?php

namespace App\Modules\Teaching_1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nganh extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'nganh';

    // Các trường có thể được gán giá trị hàng loạt
    protected $fillable = [
        'title',
        'slug',
        'donvi_id',
        'code',
        'content',
        'status',
    ];

    // Định nghĩa mối quan hệ với bảng donvi
    public function donvi()
    {
        return $this->belongsTo(Donvi::class, 'donvi_id');   
    }
}