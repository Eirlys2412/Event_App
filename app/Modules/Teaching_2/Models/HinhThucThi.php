<?php

namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhThucThi extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
    ];
}
