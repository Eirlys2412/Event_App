<?php

namespace App\Modules\Events\Models;
use App\Modules\Group\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    use HasFactory;

    protected $table = 'event_group'; // Tên bảng
    protected $fillable = [
        'group_id',
        'event_id',
    ];

    // Quan hệ với Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Quan hệ với Group
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
