<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Event\Controllers\EventController;

// Định nghĩa route với prefix và name mới
Route::prefix('admin/eventtype')->name('admin.eventtype.')->middleware('auth')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');                  // Route hiển thị danh sách sự kiện
    Route::get('create', [EventController::class, 'create'])->name('create');            // Route hiển thị form tạo sự kiện mới
    Route::post('store', [EventController::class, 'store'])->name('store');              // Route lưu sự kiện mới
    Route::get('{id}/edit', [EventController::class, 'edit'])->name('edit');             // Route hiển thị form chỉnh sửa sự kiện
    Route::put('{id}', [EventController::class, 'update'])->name('update');              // Route cập nhật sự kiện
    Route::delete('{id}', [EventController::class, 'destroy'])->name('destroy');         // Route xóa sự kiện
    Route::post('status', [EventController::class, 'eventStatus'])->name('status');      // Route cập nhật trạng thái sự kiện
});
