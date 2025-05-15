<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Events\Models\Event;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'reason'
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