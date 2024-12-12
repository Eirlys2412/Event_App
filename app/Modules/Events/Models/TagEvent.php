<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagEvent extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'event_id'];
}
