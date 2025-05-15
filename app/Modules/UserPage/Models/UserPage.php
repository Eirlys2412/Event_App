<?php

namespace App\Modules\UserPage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPage extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'items',
    ];
}
