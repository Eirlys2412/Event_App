<?php

namespace App\Modules\Teaching_2\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao;
use App\Modules\Teaching_2\Models\Hocky;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProgramDetails extends Model
{
    use HasFactory;

    protected $table = 'program_details';

    protected $fillable = [
        'hocphan_id',
        'chuongtrinh_id',
        'hoc_ky_id',
        'loai',
        'hocphantienquyet',
        'hocphansongsong',
    ];

    // protected $casts = [
    //     'hocphantienquyet' => 'array', // Tự động chuyển JSON thành mảng PHP
    //     'hocphansongsong' => 'array', // Tự động chuyển JSON thành mảng PHP
    // ];
    public function hocPhan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id');
    }
    
    public function chuongTrinhdaotao()
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'chuongtrinh_id');
    }

    public function hocKy()
    {
        return $this->belongsTo(Hocky::class, 'hoc_ky_id');
    }
}
