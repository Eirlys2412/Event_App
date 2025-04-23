<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Events\Controllers\EventController;
use App\Modules\Events\Controllers\EventGroupController;
use App\Modules\Events\Controllers\EventUserController;
use App\Modules\Events\Controllers\EventAttendanceController;
use App\Modules\Events\Controllers\EventRegistrationController;
use App\Modules\Events\Controllers\EventTypeController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Routes cho loại sự kiện
    Route::prefix('eventtype')->name('event_type.')->group(function () {
        Route::get('/', [EventTypeController::class, 'index'])->name('index');
        Route::get('/create', [EventTypeController::class, 'create'])->name('create');
        Route::post('/', [EventTypeController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventTypeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventTypeController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventTypeController::class, 'destroy'])->name('destroy');
        Route::post('/status', [EventTypeController::class, 'updateStatus'])->name('status');
        Route::get('/search', [EventTypeController::class, 'search'])->name('search');
    });

    // Routes cho quản lý sự kiện
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
    });

    // Routes cho nhóm sự kiện
    Route::prefix('eventgroup')->name('event_group.')->group(function () {
        Route::get('/', [EventGroupController::class, 'index'])->name('index');
        Route::get('/create', [EventGroupController::class, 'create'])->name('create');
        Route::post('/', [EventGroupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventGroupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventGroupController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventGroupController::class, 'destroy'])->name('destroy');
    });

    // Routes cho người dùng sự kiện
    Route::prefix('eventuser')->name('event_user.')->group(function () {
        Route::get('/', [EventUserController::class, 'index'])->name('index');
        Route::get('/create', [EventUserController::class, 'create'])->name('create');
        Route::post('/', [EventUserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventUserController::class, 'destroy'])->name('destroy');
        Route::get('/export/{eventId}', [EventUserController::class, 'export'])->name('export');
        });

    // Routes cho điểm danh sự kiện
    Route::prefix('eventattendance')->name('event_attendance.')->group(function () {
        Route::get('/', [EventAttendanceController::class, 'index'])->name('index');
        Route::get('/create', [EventAttendanceController::class, 'create'])->name('create');
        Route::post('/', [EventAttendanceController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventAttendanceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventAttendanceController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventAttendanceController::class, 'destroy'])->name('destroy');
        Route::post('/check-in', [EventAttendanceController::class, 'checkIn'])->name('check_in');
        Route::post('/status', [EventAttendanceController::class, 'updateStatus'])->name('status');
        Route::get('/generate-qr/{eventId}', [EventAttendanceController::class, 'generateQrCode'])->name('generate_qr');

    });

    // Routes cho đăng ký sự kiện
    Route::prefix('event_registration')->name('event_registration.')->group(function () {
        Route::get('/', [EventRegistrationController::class, 'index'])->name('index');
        Route::get('/create', [EventRegistrationController::class, 'create'])->name('create');
        Route::post('/', [EventRegistrationController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EventRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventRegistrationController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventRegistrationController::class, 'destroy'])->name('destroy');

    });
});
