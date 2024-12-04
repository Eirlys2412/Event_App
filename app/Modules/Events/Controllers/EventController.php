<?php

namespace App\Modules\Events\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Str;
use App\Modules\Events\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return view('Event::index');
    }
}