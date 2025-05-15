<?php

namespace App\Modules\Teaching_1\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
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

    // Quan hệ với bảng DonVi
    public function donVi(): BelongsTo
    {
        return $this->belongsTo(DonVi::class, 'ma_donvi');
    }

    // Quan hệ với bảng User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với bảng ChuyenNganh
    public function chuyenNganhs(): BelongsTo
    {
        return $this->belongsTo(ChuyenNganh::class, 'chuyen_nganh');
    }
}
