<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'user_ids', // Thêm user_id vào đây
    ];

    /**
     * Các trường kiểu JSON.
     */
    protected $casts = [
        'resources' => 'array',
        'tags' => 'array',
        'user_ids' => 'array', // Thêm user_id vào đây
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

    /**
     * Quan hệ: Event thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}