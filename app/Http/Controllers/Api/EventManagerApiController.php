<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\EventRegistration;
use App\Modules\Events\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventManagerApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Xem danh sách đăng ký cần phê duyệt
    public function pendingRegistrations()
    {
        $registrations = EventRegistration::with(['event', 'user'])
                                        ->where('status', 'pending')
                                        ->get();

        return response()->json([
            'status' => true,
            'data' => $registrations
        ]);
    }

    // Phê duyệt đăng ký
    public function approveRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        
        if ($registration->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Chỉ có thể phê duyệt đăng ký đang chờ.'
            ], 400);
        }

        $registration->update(['status' => 'approved']);

        return response()->json([
            'status' => true,
            'message' => 'Đã phê duyệt đăng ký thành công!',
            'data' => $registration
        ]);
    }

    // Từ chối đăng ký
    public function rejectRegistration($id)
    {
        $registration = EventRegistration::findOrFail($id);
        
        if ($registration->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Chỉ có thể từ chối đăng ký đang chờ.'
            ], 400);
        }

        $registration->update(['status' => 'rejected']);

        return response()->json([
            'status' => true,
            'message' => 'Đã từ chối đăng ký thành công!',
            'data' => $registration
        ]);
    }

    // Xem danh sách đăng ký của một sự kiện
    public function eventRegistrations($eventId)
    {
        $registrations = EventRegistration::with(['user'])
                                        ->where('event_id', $eventId)
                                        ->get();

        return response()->json([
            'status' => true,
            'data' => $registrations
        ]);
    }
} 