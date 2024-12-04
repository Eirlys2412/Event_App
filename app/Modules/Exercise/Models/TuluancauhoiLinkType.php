<?php

namespace App\Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuluancauhoiLinkType extends Model
{
    use HasFactory;

    protected $table = 'tuluancauhoi_link_types'; 

    protected $fillable = [
        'title',
        'code',
        'viewcode',
    ];

    
}
