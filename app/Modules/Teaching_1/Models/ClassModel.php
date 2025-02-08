<?php

namespace App\Modules\Teaching_1\Models;

use App\Modules\Teaching_1\Models\Nganh;
use App\Http\Controllers\Controller;
use App\Modules\Teaching_1\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassModel extends Model
{
    // Tên bảng
    protected $table = 'classes';

    // Các cột có thể được gán giá trị
    protected $fillable = [
        'class_name',
        'teacher_id',
        'nganh_id',
        'description',
        'max_students'
    ];

    /**
     * Lấy giảng viên liên quan đến lớp học
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Lấy ngành liên quan đến lớp học
     */
    public function nganh(): BelongsTo
    {
        return $this->belongsTo(Nganh::class, 'nganh_id');
    }
}
