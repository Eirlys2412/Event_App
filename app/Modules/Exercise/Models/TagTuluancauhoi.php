<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagTuluancauhoi extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id','resource_id' ];
}
