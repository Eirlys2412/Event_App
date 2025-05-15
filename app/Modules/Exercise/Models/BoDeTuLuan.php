<?php

namespace App\Modules\Exercise\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Teaching_2\Models\HocPhan; // Import model Module
use App\Modules\Exercise\Models\Tuluancauhoi;
use App\Modules\Teaching_3\Models\EnrollResult;
use App\Models\User; // Import model User

class BoDeTuLuan extends Model
{
    use HasFactory;

    protected $table = 'bode_tuluans';

    protected $fillable = [
        'title',
        'hocphan_id',
        'slug',
        'start_time',
        'end_time',
        'time',
        'tags',
        'user_id',
        'total_points',
        'questions',
    ];

    protected $casts = [
        'questions' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function hocphan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
/**
     * Lấy danh sách các TracNghiemCauhoi dựa vào JSON questions.
     */
    public function tuluanCauhois()
    {
        $questionIds = collect($this->questions)->pluck('id_question'); // Lấy danh sách id_question từ JSON
        return Tuluancauhoi::whereIn('id', $questionIds)->get();
    }

    public function enrollResults()
    {
        return $this->morphMany(EnrollResult::class, 'bode');
    }
}
