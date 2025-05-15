<?php

namespace App\Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'group_id',
        'user_id',
        'media',
        'status'
    ];

    protected $casts = [
        'media' => 'array',
    ];

    // Quan hệ với người tạo bài đăng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với nhóm
    public function group()
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id');
    }

    public function comments()
    {
        return $this->morphMany(\App\Modules\Comments\Models\Comment::class, 'commentable', 'item_code', 'item_id')
            ->where('item_code', 'community_post');
    }
} 