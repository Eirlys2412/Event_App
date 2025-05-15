<?php
namespace App\Modules\Events\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;
use App\Modules\Events\Models\Event;

class EventUser extends Model {
    use HasFactory;
    
    protected $table = 'event_user';
    protected $fillable = [
        'event_id',
        'user_id',
        'role_id',
        'vote'
    ];

    protected $casts = [
        'vote_count' => 'integer',
        'vote_score' => 'integer',
        'average_rating' => 'float'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function votes() {
        return $this->hasMany(EventUserVote::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class, 'user_id', 'user_id')
            ->where('event_id', $this->event_id);
    }

    // Thêm vote mới
    public function addVote($userId, $score, $comment = null) {
        $vote = $this->votes()->create([
            'voter_id' => $userId,
            'score' => $score,
            'comment' => $comment
        ]);

        // Cập nhật thống kê
        $this->vote_count++;
        $this->vote_score += $score;
        $this->average_rating = $this->vote_score / $this->vote_count;
        $this->save();

        return $vote;
    }

    // Lấy top users theo vote
    public static function getTopUsers($limit = 10) {
        return static::orderBy('average_rating', 'desc')
            ->orderBy('vote_count', 'desc')
            ->take($limit)
            ->get();
    }
}