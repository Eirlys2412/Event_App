<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Motion\Controllers\MotionController;

Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    
    // Motion section
    Route::resource('motion', MotionController::class);
    Route::post('motion_status', [MotionController::class, 'motionStatus'])->name('motion.status');
    Route::post('upload/icon', [YourController::class, 'uploadIcon'])->name('upload.icon'); // Nếu cần

});