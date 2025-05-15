<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;


class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';

    protected $fillable = [
        'tkb_id',
        'student_list',
        'student_list'
    ];
    public function thoikhoabieu()
    {
        return $this->belongsTo(ThoiKhoaBieu::class, 'tkb_id');
    }
}
