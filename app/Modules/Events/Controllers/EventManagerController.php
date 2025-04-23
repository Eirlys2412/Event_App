<?php

namespace App\Modules\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\Event;
use App\Modules\Events\Models\EventRegistration;
use App\Models\User;
class EventManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $active_menu = 'event_manager';
        $events = Event::all();
        $users = User::all();
        $registrations = EventRegistration::all();
        
        return view('Event::events.index', compact('events', 'users', 'registrations', 'active_menu'));
    }

    public function create(){
        $active_menu = 'event_manager';
        $events = Event::all();
        $users = User::all();
        return view('Event::events.create', compact('events', 'users', 'active_menu'));
    }


}

