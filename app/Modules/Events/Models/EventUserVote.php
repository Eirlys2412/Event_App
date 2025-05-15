<?php
namespace App\Modules\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EventUserVote extends Model
{
    use HasFactory;

    protected $table = 'event_user_votes';
    
    protected $fillable = [
        'voter_id',
        'event_user_id',
        'score',
        'comment'
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function eventUser()
    {
        return $this->belongsTo(EventUser::class);
    }
} 