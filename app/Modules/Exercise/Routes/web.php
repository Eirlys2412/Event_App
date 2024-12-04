<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Exercise\Controllers\TuluancauhoiController;
use App\Modules\Exercise\Controllers\TuluancauhoiTypeController;
use App\Modules\Exercise\Controllers\TuluancauhoiLinkTypeController;

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
    // Phần quản lý Loại Câu hỏi Tự luận
    Route::resource('tuluancauhoi-types', TuluancauhoiTypeController::class)->names([
        'index' => 'tuluancauhoi-types.index',
        'create' => 'tuluancauhoi-types.create',
        'store' => 'tuluancauhoi-types.store',
        'edit' => 'tuluancauhoi-types.edit',
        'update' => 'tuluancauhoi-types.update',
        'destroy' => 'tuluancauhoi-types.destroy',
    ]); 

    // Phần quản lý Loại Liên kết Câu hỏi Tự luận
    Route::resource('tuluancauhoi-link-types', TuluancauhoiLinkTypeController::class)->names([
        'index' => 'tuluancauhoi-link-types.index',
        'create' => 'tuluancauhoi-link-types.create',
        'store' => 'tuluancauhoi-link-types.store',
        'edit' => 'tuluancauhoi-link-types.edit',
        'update' => 'tuluancauhoi-link-types.update',
        'destroy' => 'tuluancauhoi-link-types.destroy',
    ]); 
});