<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPoint extends Model
{
    use HasFactory;

    protected $fillable = ['func_cmd', 'point'];
}

