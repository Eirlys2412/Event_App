<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Events\Models\TagEvent;
use App\Modules\TuongTac\Models\Like;
use App\Modules\TuongTac\Models\Bookmark;
use App\Modules\TuongTac\Models\Vote;
class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title',
    'slug', 
    'tags',
    'photo',
    'summary',
    'content',
    'cat_id',
    'user_id',
    'status'];

    public function tags()
{
    // Lấy tất cả tag từ bảng 'tags' dựa trên id trong cột 'tags' (mảng JSON)
    $tagsArray = json_decode($this->tags, true);  // Chuyển 'tags' thành mảng

    // Lấy các tag từ bảng 'tags' với id có trong mảng
    return TagEvent::whereIn('id', $tagsArray)->get(); 
}

public function comments()
{
    return $this->morphMany(\App\Modules\Comments\Models\Comment::class, 'commentable', 'item_code', 'item_id')
        ->where('item_code', 'blog');
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
// App\Models\Blog.php
public function scopeApproved($query)
{
    return $query->where('status', 'approved');
}



}