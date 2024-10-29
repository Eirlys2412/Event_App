<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceLinkType extends Model
{
    use HasFactory;

    protected $table = 'resource_link_types'; 

    protected $fillable = [
        'title',
        'code',
        'viewcode',
    ];

    
}
