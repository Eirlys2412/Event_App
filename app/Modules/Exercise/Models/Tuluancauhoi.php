<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Exercise\Models\TuLuanDapan; 
use App\Modules\Teaching_2\Models\HocPhan; // Import model Module
use App\Models\User; // Import model User

class TuLuanCauHoi extends Model
{
    //
    use HasFactory;
    protected $table = 'tu_luan_cauhois';
    protected $fillable = [
        'content',
        'hocphan_id',
        'user_id',
        'tags',
        'resources',
    ];
    public function hocphan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function answers()
{
    return $this->hasMany(TuLuanDapan::class, 'tu_luan_id');
}
}
