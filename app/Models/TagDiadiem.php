<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagDiadiem extends Model
{
    use HasFactory;

    protected $table = 'tag_diadiems'; // Tên bảng

    protected $fillable = [
        'tag_id',     // ID của tag
        'diadiems_id' // ID của địa điểm
    ];
}