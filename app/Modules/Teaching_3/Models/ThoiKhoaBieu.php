<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\DiaDiem;

class ThoiKhoaBieu extends Model
{
    use HasFactory;

    protected $table = 'thoi_khoa_bieus';

    protected $fillable = [
        'phancong_id',
        'diadiem_id',
        'buoi',
        'ngay',
        'tietdau',
        'tietcuoi',
    ];

    public function phancong()
    {
        return $this->belongsTo(PhanCong::class, 'phancong_id');
    }

    public function diadiem()
    {
        return $this->belongsTo(DiaDiem::class, 'dia_diem_id');
    }
}
