<?php

namespace App\Modules\Exercise\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Exercise\Models\TracNghiemCauhoi;

class TracNghiemDapan extends Model
{
    use HasFactory;

    protected $table = 'trac_nghiem_dapans';

    protected $fillable = [
        'tracnghiem_id',
        'content',
        'resounce_list', // JSON chứa danh sách tài nguyên
        'is_correct',
    ];

    protected $casts = [
        'resounce_list' => 'array',
        'is_correct' => 'boolean',
    ];

    // Quan hệ với câu hỏi trắc nghiệm
    public function tracnghiemCauhoi()
    {
        return $this->belongsTo(TracNghiemCauhoi::class, 'tracnghiem_id');
    }
}
