<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPayment extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'order_id', 'amount', 'payment_url', 'result_code', 'message',
    ];
}
