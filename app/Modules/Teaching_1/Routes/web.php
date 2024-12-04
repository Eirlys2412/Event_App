<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Teaching_1\Controllers\NganhController;
use App\Modules\Teaching_1\Controllers\TeacherController;
use App\Modules\Teaching_1\Controllers\DonviController;
use App\Modules\Teaching_1\Controllers\StudentController;


// Nhóm route cho quản lý ngành
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('nganh', NganhController::class);
    // Route cho danh sách ngành
    //Route::get('nganh', [NganhController::class, 'index'])->name('nganh.index');

    // Route cho thêm ngành
    //Route::get('nganh/create', [NganhController::class, 'create'])->name('nganh.create');
    //Route::post('nganh', [NganhController::class, 'store'])->name('nganh.store');

    // Route cho chỉnh sửa ngành
    //Route::get('nganh/{id}/edit', [NganhController::class, 'edit'])->name('nganh.edit');
    //Route::patch('nganh/{id}', [NganhController::class, 'update'])->name('nganh.update');

    // Route cho xóa ngành
    //Route::delete('nganh/{id}', [NganhController::class, 'destroy'])->name('nganh.destroy');

    // Route cho tìm kiếm ngành (nếu có)
    Route::get('nganh/search', [NganhController::class, 'nganhSearch'])->name('nganh.search');
    Route::post('nganh/status', [NganhController::class, 'nganhStatus'])->name('nganh.status');
    // Route cho giảng viên
    Route::get('teacher/search', [TeacherController::class, 'search'])->name('teacher.search');
    Route::resource('teacher', TeacherController::class);
    
    // Route cho don vi
    Route::resource('donvi', DonviController::class);

});
Route::middleware('auth')->group(function () {
    Route::get('students', [StudentController::class, 'index'])->name('student.index');
    Route::get('students/create', [StudentController::class, 'create'])->name('student.create');
    Route::post('students', [StudentController::class, 'store'])->name('student.store');
    Route::get('students/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('students/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('students/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
});