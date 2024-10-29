<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagResource extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id','resource_id' ];
}
