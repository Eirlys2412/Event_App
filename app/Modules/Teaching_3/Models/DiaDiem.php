<?php

namespace App\Modules\Teaching_3\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiaDiem extends Model
{
    use HasFactory;
    protected $table = 'dia_diem'; // Tên bảng trong database

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
    ];

}
