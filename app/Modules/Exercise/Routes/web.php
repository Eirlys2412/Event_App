<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Exercise\Controllers\TracNghiemCauHoiController;
use App\Modules\Exercise\Controllers\TuLuanCauHoiController;

Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('tracnghiemcauhoi', TracNghiemCauHoiController::class);
    // Route::get('hocphan_search', [App\Modules\Exercise\Controllers\::class, 'moduleSearch'])->name('hocphan.search');
    Route::delete('/admin/tracnghiemcauhoi/{tracnghiemcauhoiId}/resource/{resourceId}', [TracNghiemCauHoiController::class, 'removeResource'])->name('tracnghiemcauhoi.removeResource');

    Route::resource('tuluancauhoi', TuLuanCauHoiController::class);
    Route::delete('/admin/tuluancauhoi/{tuluancauhoiId}/resource/{resourceId}', [TuLuanCauHoiController::class, 'removeResource'])->name('tuluancauhoi.removeResource');
});

