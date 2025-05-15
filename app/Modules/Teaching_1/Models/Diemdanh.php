<?php
namespace App\Modules\Teaching_1\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_1\Models\Student; 
use App\Modules\Teaching_2\Models\HocPhan;

class Diemdanh extends Model
{
    protected $table = 'diemdanh';  // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'diemdanh_id';  // Nếu cột khóa chính là 'id', không cần chỉ định lại
    public $timestamps = false;  // Nếu bảng không sử dụng timestamps, set false

    protected $fillable = [
        'sinhvien_id',
        'hocphan_id',
        'time',
        'trangthai',
    ];

    // Quan hệ với bảng sinh viên (students)
    public function student()
    {
        return $this->belongsTo(Student::class, 'sinhvien_id', 'id');
    }

    // Quan hệ với bảng học phần (hocphan)
    public function hocphan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id', 'id');
    }
}

