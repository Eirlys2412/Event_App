<?php

namespace App\Modules\item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemType extends Model
{
    protected $fillable = ['title', 'blog', 'event', 'book', 'resources', 'comments', 'groups', 'phancong', 'song', 'item_code'];
}
