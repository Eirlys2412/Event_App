<?php

namespace App\Modules\Teaching_1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'mssv',
        'khoa',
        'donvi_id',
        'nganh_id',
        'class_id',
        'user_id',
        'status',
        'slug',
    ];

    public function donvi()
    {
        return $this->belongsTo(\App\Modules\Teaching_1\Models\Donvi::class, 'donvi_id');
    }

    public function nganh()
    {
        return $this->belongsTo(\App\Modules\Teaching_1\Models\Nganh::class, 'nganh_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function classes()
    {
        return $this->belongsTo(\App\Modules\Teaching_1\Models\ClassModel::class, 'class_id');
    }
    
}
