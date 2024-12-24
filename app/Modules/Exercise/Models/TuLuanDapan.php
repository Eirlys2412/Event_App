<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Exercise\Models\Tuluancauhoi;
class TuLuanDapan extends Model
{
    //
    use HasFactory;

    protected $table = 'tu_luan_dapans';

    protected $fillable = [
        'tu_luan_id',
        'content',
        'resounce_list', // JSON chứa danh sách tài nguyên
    ];

    protected $casts = [
        'resounce_list' => 'array',
    ];

    // Quan hệ với câu hỏi trắc nghiệm
    public function tuluanCauhoi()
    {
        return $this->belongsTo(Tuluancauhoi::class, 'tu_luan_id');
    }
}
