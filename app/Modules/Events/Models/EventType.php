<?php
namespace App\Modules\Events\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;
    protected $table = 'event_type';
    protected $fillable = [
        'title',
        'slug',
        'location_type',
        'location_address',
        'status',
    ];
}