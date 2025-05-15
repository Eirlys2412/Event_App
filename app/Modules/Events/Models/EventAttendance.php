<?php
namespace App\Modules\Events\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventAttendance extends Model {
    use HasFactory;
    protected $table = 'event_attendance'; // Tên bảng
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'qr_token',
        'checked_in_at',
        'check_in_location',
        'qr_code_data'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function eventUser()
    {
        return $this->belongsTo(EventUser::class, 'user_id', 'user_id')
            ->where('event_id', $this->event_id);
    }

    public function generateQrToken() {
        $this->qr_token = bin2hex(random_bytes(16));
        $this->save();
        return $this->qr_token;
    }

    public function checkIn($eventId, Request $request)
    {
        // ... các kiểm tra khác

        $userId = Auth::id();

        $attendance = EventAttendance::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'checked_in_at' => now(),
            'qr_token' => $request->qr_token
        ]);
        // ...
    }

    public function isCheckedIn() {
        return !is_null($this->checked_in_at);
    }
}