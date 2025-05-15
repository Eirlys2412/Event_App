<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'private', 'category'
    ];
}
