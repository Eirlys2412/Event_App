<?php

namespace App\Modules\Teaching_1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuyenNganh extends Model
{
    use HasFactory;

    protected $table = 'chuyennganhs';

    protected $fillable = [
        'title',
        'slug',
        'status',
    ];
}