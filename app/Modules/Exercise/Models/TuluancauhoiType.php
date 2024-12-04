<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuluancauhoiType extends Model
{
    use HasFactory;

    protected $table = 'tuluancauhoi_types'; // Tên bảng trong CSDL

    protected $fillable = [
        'title',
        'code',
    ];

    // Nếu cần thiết, bạn có thể thêm các phương thức quan hệ ở đây
}
