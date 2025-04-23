<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\Vote;

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
        'diadiem',
        'event_type_id',
        'tags',
        'ticket_price',
        'available_tickets'
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

    public function comments()
{
    return $this->morphMany(\App\Modules\Comments\Models\Comment::class, 'commentable', 'item_code', 'item_id')
        ->where('item_code', 'event');
}

public function likes()
{
    return $this->morphMany(Like::class, 'likeable');
}

public function bookmarks() 
{
    return $this->morphMany(Bookmark::class, 'bookmarkable');
}
public function votes() {
    return $this->morphMany(Vote::class, 'votable');
}

public function averageRating() {
    return $this->votes()->avg('rating');
}

    /**
     * Quan hệ: Event thuộc về một người dùng.
     */
    
}