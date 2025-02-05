<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use App\Models\User;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_1\Models\Donvi;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_3\Models\LoaiChungchi;

class EnrollCertificate extends Model
{
    use HasFactory;

    protected $table = 'enroll_certificates';
    protected $fillable = [
        'user_id',
        'ketqua',
        'nguoicap_id',
        'donvi_id',
        'phancong_id',
        'enroll_id',
        'loai_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'nguoicap_id');
    }
    public function donvi()
    {
        return $this->belongsTo(Donvi::class, 'donvi_id');
    }
    public function phancong()
    {
        return $this->belongsTo(Phancong::class, 'phancong_id');
    }
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enroll_id');
    }
    public function loaiChungChi()
    {
        return $this->belongsTo(LoaiChungChi::class, 'loai_id');
    }
}