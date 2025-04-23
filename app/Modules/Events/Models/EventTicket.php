<?php

namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTicket extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'quantity',
        'ticket_type',
        'transaction_id',
        'status'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\VNPay\Models\VNPayTransaction::class);
    }
} 