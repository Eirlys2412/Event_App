<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Events\Controllers\EventTypeController;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::prefix('eventtype')->name('event_type.')->group(function () {
        Route::get('/', [EventTypeController::class, 'index'])->name('index');
        Route::get('/create', [EventTypeController::class, 'create'])->name('create');
        Route::post('/store', [EventTypeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EventTypeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EventTypeController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [EventTypeController::class, 'destroy'])->name('destroy');
        Route::post('/status', [EventTypeController::class, 'updateStatus'])->name('status');
        Route::get('/search', [EventTypeController::class, 'search'])->name('search');
    });
});
