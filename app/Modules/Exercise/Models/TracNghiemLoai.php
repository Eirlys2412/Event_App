<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TracNghiemLoai extends Model
{
    //
    use HasFactory;

    protected $table = 'trac_nghiem_loais';

    protected $fillable = [
        'title', // Cột title
        'status', // Cột status
    ];
}
