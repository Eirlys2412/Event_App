<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\DiaDiem;

class LichThi extends Model
{
    use HasFactory;

    protected $table = 'lich_thi';

    protected $fillable = [
        'phancong_id',
        'buoi',
        'ngay1',
        'ngay2',
        'dia_diem_thi',
    ];

    public function phancong()
    {
        return $this->belongsTo(PhanCong::class, 'phancong_id');
    }

    public function diadiem()
    {
        return $this->belongsTo(DiaDiem::class, 'dia_diem_thi');
    }
}
