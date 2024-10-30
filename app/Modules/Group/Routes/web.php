<?php

use App\Modules\Group\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Modules\Group\Controllers\GroupMemberController;


// Định nghĩa các route ở đây
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    
    // Phần nhóm
    Route::resource('group', GroupController::class);
    
});

    Route::prefix('admin/groupmembers')->group(function () {
        Route::get('/{groupId}', [GroupMemberController::class, 'index'])->name('admin.groupmember.index'); // Danh sách thành viên nhóm
        Route::get('/{groupId}/create', [GroupMemberController::class, 'create'])->name('admin.groupmember.create'); // Tạo thành viên nhóm
        Route::post('/{groupId}', [GroupMemberController::class, 'store'])->name('admin.groupmember.store'); // Lưu thành viên nhóm mới
        // Route::get('/edit/{id}', [GroupMemberController::class, 'edit'])->name('admin.groupmember.edit'); // Chỉnh sửa thành viên
        Route::get('/{groupId}/edit/{id}', [GroupMemberController::class, 'edit'])->name('admin.groupmember.edit');
        Route::patch('/{groupId}/{id}', [GroupMemberController::class, 'update'])->name('admin.groupmember.update'); // Cập nhật thành viên
        Route::delete('/{id}', [GroupMemberController::class, 'destroy'])->name('admin.groupmember.destroy'); // Xóa thành viên
    });
