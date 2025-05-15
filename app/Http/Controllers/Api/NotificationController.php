<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // GET /api/v1/notifications
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications, 200);
    }

    // POST /api/v1/notifications/mark-read
    public function markAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => true], 200);
    }
}