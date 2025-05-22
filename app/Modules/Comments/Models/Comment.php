<?php

namespace App\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\TuongTac\Models\Like;

class Comment extends Model
{
    protected $fillable = [
        'item_id',
        'item_code',
        'user_id',
        'content',
        'parent_id',
        'comment_resources',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
    
    

    public function commentable()
    {
        return $this->morphTo('commentable', 'item_code', 'item_id');
    }
    
    public function getResourcesUrlAttribute()
    {
        return $this->comment_resources 
            ? url('storage/' . $this->comment_resources) 
            : null;
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getIsLikedAttribute()
    {
        if (auth('api')->check()) {
            return $this->likes()->where('user_id', auth('api')->id())->exists();
        }
        return false;
    }
}