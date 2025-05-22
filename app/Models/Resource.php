<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'file_type',
        'url',
        'type'
    ];

    public function getFullUrlAttribute()
    {
        return url($this->url);
    }
} 