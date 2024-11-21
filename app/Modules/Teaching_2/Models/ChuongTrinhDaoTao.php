<?php
namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Teaching_1\Models\Nganh;
use App\Models\User;

class ChuongTrinhDaoTao extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'chuong_trinh_dao_tao';

    // Các cột được phép điền giá trị
    protected $fillable = [
        'nganh_id', 
        'title', 
        'content', 
        'user_id', 
        'tong_tin_chi', 
        'status',
    ];

    // Liên kết với bảng ngành
    public function nganh()
    {
        return $this->belongsTo(Nganh::class, 'nganh_id');
    }

    // Liên kết với bảng user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
