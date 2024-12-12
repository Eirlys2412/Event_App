<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event'; // Tên bảng

    /**
     * Các trường có thể gán hàng loạt.
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'resources',
        'timestart',
        'timeend',
        'event_type_id',
        'tags',
    ];

    /**
     * Các trường kiểu JSON.
     */
    protected $casts = [
        'resources' => 'array',
        'tags' => 'array',
        'timestart' => 'datetime',
        'timeend' => 'datetime',
    ];

    /**
     * Quan hệ: Event thuộc về một loại sự kiện.
     */
    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
}
