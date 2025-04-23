<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    protected $fillable = ['user_id','likeable_type','likeable_id'];

    // The user who liked
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // The liked model (Blog, Comment, Event, etc.)
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}