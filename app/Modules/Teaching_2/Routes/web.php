<?php

use App\Modules\Teaching_2\Controllers\ChuongTrinhDaoTaoController;
use Illuminate\Support\Facades\Route;
use App\Modules\Teaching_2\Controllers\ModuleController;
// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function() {
    // Route::resource('recommend', RecommendController::class);
    Route::resource('module', ModuleController::class);
    // Route::get('recommend', [RecommendController::class, 'index'])->name('recommend.index');
    Route::get('module_search', [ModuleController::class, 'moduleSearch'])->name('module.search');
    // Hiển thị danh sách chương trình đào tạo
    Route::get('chuong_trinh_dao_tao', [ChuongTrinhDaoTaoController::class, 'index'])->name('chuong_trinh_dao_tao.index');

    // Hiển thị form tạo mới chương trình đào tạo
    Route::get('chuong_trinh_dao_tao/create', [ChuongTrinhDaoTaoController::class, 'create'])->name('chuong_trinh_dao_tao.create');

    // Lưu thông tin chương trình đào tạo mới
    Route::post('chuong_trinh_dao_tao', [ChuongTrinhDaoTaoController::class, 'store'])->name('chuong_trinh_dao_tao.store');

    // Hiển thị form chỉnh sửa chương trình đào tạo
    Route::get('chuong_trinh_dao_tao/{id}/edit', [ChuongTrinhDaoTaoController::class, 'edit'])->name('chuong_trinh_dao_tao.edit');

    // Cập nhật thông tin chương trình đào tạo
    Route::put('chuong_trinh_dao_tao/{id}', [ChuongTrinhDaoTaoController::class, 'update'])->name('chuong_trinh_dao_tao.update');

    // Xóa chương trình đào tạo
    Route::delete('chuong_trinh_dao_tao/{id}', [ChuongTrinhDaoTaoController::class, 'destroy'])->name('chuong_trinh_dao_tao.destroy');

    // Cập nhật trạng thái chương trình đào tạo
    Route::post('chuong_trinh_dao_tao/status', [ChuongTrinhDaoTaoController::class, 'ChuongTrinhDaoTaoStatus'])->name('chuong_trinh_dao_tao.status');

    // Tìm kiếm chương trình đào tạo
    Route::get('chuong_trinh_dao_tao/search', [ChuongTrinhDaoTaoController::class, 'search'])->name('chuong_trinh_dao_tao.search');
});


