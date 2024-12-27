<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Group\Models\Group;
use App\Modules\Teaching_2\Models\PhanCong;
class PhancongGroup extends Model
{
    use HasFactory;

    protected $table = 'phanconggroup';

    protected $fillable = [
        'group_id',
        'phancong_id',
    ];

    // Liên kết với bảng group
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Liên kết với bảng phancong
    public function phancong()
    {
        return $this->belongsTo(Phancong::class, 'phancong_id');
    }
}
