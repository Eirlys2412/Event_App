<?php

namespace app\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import model User
use App\Modules\Teaching_2\Models\HinhThucThi;


class EnrollResult extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'enroll_id',
        'user_id',
        'hinhthucthi_id',
        'bode_type',
        'bode_id',
        'grade',
        'chitiet',
    ];

    /**
     * Polymorphic relationship with BoDeTuLuan and BoDeTracNghiem.
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(User::class, 'enroll_id');
    }

    public function hinhthucthi()
    {
        return $this->belongsTo(HinhThucThi::class, 'hinhthucthi_id');
    }

    public function bode()
    {
        return $this->morphTo();
    }
}
