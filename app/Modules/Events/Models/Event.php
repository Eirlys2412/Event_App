<?php
namespace App\Modules\Events\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'event';
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'resources',
        'timestart',
        'timeend',
        'diadiem_id',
        'tags',
    ];

    public function event_type()
    {
        return $this->belongsTo(EventType::class,);
    }
}