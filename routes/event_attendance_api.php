<?php

use Illuminate\Support\Facades\Route;

// API routes for Event Attendance and QR Code scanning
Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {
    // Điểm danh qua QR - Route mới (recommended)
    Route::post('/check-in/qr', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'checkInByQr']);// 
    
    // Điểm danh qua QR - Route cũ (for backward compatibility)
    Route::post('/check-in/{eventId}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'checkInByQr']);
}); 