<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Teaching_3\Controllers\ThoiKhoaBieuController;
use App\Modules\Teaching_3\Controllers\AttendanceController;

Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    // Route::resource('tracnghiemcauhoi', TracNghiemCauHoiController::class);
    // // Route::get('hocphan_search', [App\Modules\Exercise\Controllers\::class, 'moduleSearch'])->name('hocphan.search');
    // Route::delete('/admin/tracnghiemcauhoi/{tracnghiemcauhoiId}/resource/{resourceId}', [TracNghiemCauHoiController::class, 'removeResource'])->name('tracnghiemcauhoi.removeResource');
    Route::resource('thoikhoabieu', \App\Modules\Teaching_3\Controllers\ThoiKhoaBieuController::class);
    Route::resource('diemdanh', \App\Modules\Teaching_3\Controllers\AttendanceController::class);
    Route::get('diemdanh/{id}', [AttendanceController::class, 'show'])->name('admin.diemdanh.show');
});

