<?php

namespace App\Modules\Event\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'eventtype'; // Tên bảng

    protected $fillable = [
        'title',        // Tên sự kiện
        'slug', 
        'description',   
        'status',       // Trạng thái sự kiện
    ];

    /**
     * Trả về slug của sự kiện
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
