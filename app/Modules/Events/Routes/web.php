<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Events\Controllers\EventTypeController;
use App\Modules\Events\Controllers\EventController;
use App\Modules\Events\Controllers\EventGroupController;

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

    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/store', [EventController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EventController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('destroy');
        Route::delete('/{eventId}/resource/{resourceId}', [EventController::class, 'removeResource'])->name('resource.destroy');
    });

    Route::prefix('eventgroup')->name('event_group.')->group(function () {
        Route::get('/', [EventGroupController::class, 'index'])->name('index');
        Route::get('/create', [EventGroupController::class, 'create'])->name('create');
        Route::post('/store', [EventGroupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EventGroupController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EventGroupController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [EventGroupController::class, 'destroy'])->name('destroy');
    });
});
