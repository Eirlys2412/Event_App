<?php

namespace App\Modules\TuongTac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Modules\TuongTac\Models\Like;
use App\Models\User;

class Vote extends Model
{
    protected $fillable = ['user_id','votable_type','votable_id','rating'];

    // The voted model (Blog, Event, User, etc.)
    public function votable(): MorphTo
    {
        return $this->morphTo();
    }
    public function likeable() {
        return $this->morphMany(Like::class, 'likeable');
    }
} 