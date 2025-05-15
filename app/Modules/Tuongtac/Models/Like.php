<?php

namespace App\Modules\TuongTac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

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