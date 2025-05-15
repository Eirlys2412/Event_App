<?php

namespace app\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Teaching_1\Models\Student;
use App\Modules\Teaching_2\Models\PhanCong;
class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'phancong_id', 'timespending', 'process', 'status'];

    // Liên kết với bảng user
    public function students()
    {
        return $this->belongsTo(Student::class);
    }

    // Liên kết với bảng phân công
    public function phancong()
    {
        return $this->belongsTo(Phancong::class);
    }
}
