<?php

// app/Modules/Teaching_2/Models/Hocki.php

namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hocky extends Model
{
    use HasFactory;

    protected $table = 'hoc_ky'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = [
        'so_hoc_ky', // Tên học kỳ
    ];

    // Định nghĩa các quan hệ nếu có
}
