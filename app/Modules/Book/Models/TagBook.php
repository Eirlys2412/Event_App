<?php

namespace App\Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagBook extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id','book_id' ];
}
