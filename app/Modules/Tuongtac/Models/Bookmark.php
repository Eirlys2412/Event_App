<?php

namespace App\Modules\TuongTac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class Bookmark extends Model
{
    protected $fillable = ['user_id','bookmarkable_type','bookmarkable_id'];

    // The user who bookmarked
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // The bookmarked model (Blog, Event, User, etc.)
    public function bookmarkable(): MorphTo
    {
        return $this->morphTo();
    }
} 