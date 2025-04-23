<?php

namespace App\Exports;

use App\Modules\Events\Models\EventUser;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EventUsersExport implements FromView
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function view(): View
    {
        $eventUsers = EventUser::with(['user', 'event', 'role'])
            ->where('event_id', $this->eventId)
            ->get();

        return view('Events::event_user.export', compact('eventUsers'));
    }
}

