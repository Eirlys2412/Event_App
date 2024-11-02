<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teacher'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = [
        'mgv',
        'ma_donvi',
        'user_id',
        'chuyen_nganh',
        'hoc_ham',
        'hoc_vi',
        'loai_giangvien',
    ];

    // Khai báo quan hệ với bảng donvi
    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'ma_donvi');
    }

    // Khai báo quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Khai báo quan hệ với bảng chuyen_nganh
    public function chuyenNganh()
    {
        return $this->belongsTo(ChuyenNganh::class, 'chuyen_nganh');
    }
}
