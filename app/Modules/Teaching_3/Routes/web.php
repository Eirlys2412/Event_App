<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Teaching_3\Controllers\ThoiKhoaBieuController;
use App\Modules\Teaching_3\Controllers\AttendanceController;
use App\Modules\Teaching_3\Controllers\PhancongGroupController;
use App\Modules\Teaching_3\Controllers\LoaiChungchiController;
use App\Modules\Teaching_3\Controllers\EnrollmentController;
use App\Modules\Teaching_3\Controllers\EnrollCertificatesController;
use App\Modules\Teaching_3\Controllers\LearningController;
use App\Modules\Teaching_3\Controllers\EnrollResultController;
use App\Modules\Teaching_3\Controllers\LichThiController;

Route::prefix('phanconggroup')->group(function () {
    Route::get('/', [PhancongGroupController::class, 'index'])->name('phanconggroup.index');
    Route::get('/create', [PhancongGroupController::class, 'create'])->name('phanconggroup.create');
    Route::post('/', [PhancongGroupController::class, 'store'])->name('phanconggroup.store');
    Route::get('/{id}/edit', [PhancongGroupController::class, 'edit'])->name('phanconggroup.edit');
    Route::put('/{id}', [PhancongGroupController::class, 'update'])->name('phanconggroup.update');
    Route::delete('/{id}', [PhancongGroupController::class, 'destroy'])->name('phanconggroup.destroy');
});



Route::prefix('loai-chung-chi')->group(function () {
    Route::get('/', [LoaiChungchiController::class, 'index'])->name('loai_chungchi.index');
    Route::get('/create', [LoaiChungchiController::class, 'create'])->name('loai_chungchi.create');
    Route::post('/', [LoaiChungchiController::class, 'store'])->name('loai_chungchi.store');
    Route::get('/{id}/edit', [LoaiChungchiController::class, 'edit'])->name('loai_chungchi.edit');
    Route::put('/{id}', [LoaiChungchiController::class, 'update'])->name('loai_chungchi.update');
    Route::delete('/{id}', [LoaiChungchiController::class, 'destroy'])->name('loai_chungchi.destroy');
});

Route::prefix('enrollments')->group(function () {
    // Hiển thị danh sách tất cả enrollments
    Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment.index');

    // Hiển thị form tạo mới enrollment
    Route::get('/create', [EnrollmentController::class, 'create'])->name('enrollment.create');

    // Lưu enrollment mới vào cơ sở dữ liệu
    Route::post('/', [EnrollmentController::class, 'store'])->name('enrollment.store');

    // Hiển thị chi tiết một enrollment cụ thể
    Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollment.show');

    // Hiển thị form chỉnh sửa enrollment
    Route::get('/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('enrollment.edit');

    // Cập nhật thông tin enrollment
    Route::put('/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollment.update');

    // Xóa một enrollment
    Route::delete('/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');
});
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
    // Route::resource('tracnghiemcauhoi', TracNghiemCauHoiController::class);
    // // Route::get('hocphan_search', [App\Modules\Exercise\Controllers\::class, 'moduleSearch'])->name('hocphan.search');
    // Route::delete('/admin/tracnghiemcauhoi/{tracnghiemcauhoiId}/resource/{resourceId}', [TracNghiemCauHoiController::class, 'removeResource'])->name('tracnghiemcauhoi.removeResource');
    Route::resource('thoikhoabieu', ThoiKhoaBieuController::class);
    Route::resource('lichthi', LichThiController::class);
    Route::resource('diemdanh', AttendanceController::class);
    Route::get('diemdanh/{id}', [AttendanceController::class, 'show'])->name('admin.diemdanh.show');
    Route::resource('enrollcertificates', EnrollCertificatesController::class);
    Route::resource('learning', LearningController::class);
    Route::resource('enroll_results', EnrollResultController::class);
});


