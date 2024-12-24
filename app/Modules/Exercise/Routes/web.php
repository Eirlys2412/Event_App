<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Exercise\Controllers\TracNghiemCauHoiController;
use App\Modules\Exercise\Controllers\TuLuanCauHoiController;
use App\Modules\Exercise\Controllers\BoDeTracNghiemController;

// Định nghĩa route cho module câu hỏi
Route::prefix('admin/tuluancauhoi')->name('admin.tuluancauhoi.')->group(function () {
    Route::get('/', [TuluancauhoiController::class, 'index'])->name('index');
    Route::get('/create', [TuluancauhoiController::class, 'create'])->name('create');
    Route::post('/store', [TuluancauhoiController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TuluancauhoiController::class, 'edit'])->name('edit');
    Route::patch('/{id}', [TuluancauhoiController::class, 'update'])->name('update');
    Route::delete('/{id}', [TuluancauhoiController::class, 'destroy'])->name('destroy');
    Route::get('/search', [TuluancauhoiController::class, 'search'])->name('search');
    Route::get('/{id}', [TuluancauhoiController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    Route::resource('tracnghiemcauhoi', TracNghiemCauHoiController::class);
    // Route::get('hocphan_search', [App\Modules\Exercise\Controllers\::class, 'moduleSearch'])->name('hocphan.search');
    Route::delete('/admin/tracnghiemcauhoi/{tracnghiemcauhoiId}/resource/{resourceId}', [TracNghiemCauHoiController::class, 'removeResource'])->name('tracnghiemcauhoi.removeResource');

    Route::resource('tuluancauhoi', TuLuanCauHoiController::class);
    Route::delete('/admin/tuluancauhoi/{tuluancauhoiId}/resource/{resourceId}', [TuLuanCauHoiController::class, 'removeResource'])->name('tuluancauhoi.removeResource');
    
    // Bộ đề trắc nghiệm
    Route::resource('bode_tracnghiem', BoDeTracNghiemController::class);
});

