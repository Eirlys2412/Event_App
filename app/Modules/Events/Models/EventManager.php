<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Events\Models\Event;

class EventManager extends Model
{
    use HasFactory;

    protected $table = 'event_manager';
    protected $fillable = [
        
        'user_id',
        'slug'
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
} 