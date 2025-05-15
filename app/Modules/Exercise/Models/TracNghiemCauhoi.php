<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Exercise\Models\TracNghiemLoai; 
use App\Modules\Exercise\Models\TracNghiemDapan; 
use App\Modules\Teaching_2\Models\HocPhan; // Import model Module
use App\Models\User; // Import model User

class TracNghiemCauhoi extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu khác với quy tắc mặc định
    protected $table = 'trac_nghiem_cauhois';

    // Các trường có thể gán đại chúng
    protected $fillable = [
        'content',
        'hocphan_id',
        'tags',
        'resource',
        'loai_id',
        'user_id',
    ];

    // Định nghĩa mối quan hệ với bảng khác
    public function loaicauhoi()
{
    return $this->belongsTo(TracNghiemLoai::class, 'loai_id');
}
    public function hocphan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function answers()
{
    return $this->hasMany(TracNghiemDapan::class, 'tracnghiem_id');
}

}
