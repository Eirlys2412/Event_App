<?php

namespace app\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Teaching_2\Models\PhanCong;

class Learning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phancong_id',
        'noidung_id',
        'time_spending',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phancong()
    {
        return $this->belongsTo(Phancong::class);
    }

    // public function noidung()
    // {
    //     return $this->belongsTo(Noidung::class);
    // }
}
