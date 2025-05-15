<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Community\Controllers\CommunityGroupController;
use App\Modules\Community\Controllers\CommunityPostController;
use App\Modules\Community\Controllers\CommunityMemberController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'auth']], function () {
    // Routes cho Community Groups
    Route::prefix('community')->name('community.')->group(function () {
        // Group routes
        Route::get('groups', [CommunityGroupController::class, 'index'])->name('groups.index');
        Route::get('groups/create', [CommunityGroupController::class, 'create'])->name('groups.create');
        Route::post('groups', [CommunityGroupController::class, 'store'])->name('groups.store');
        Route::get('groups/{id}', [CommunityGroupController::class, 'show'])->name('groups.show');
        Route::get('groups/{id}/edit', [CommunityGroupController::class, 'edit'])->name('groups.edit');
        Route::put('groups/{id}', [CommunityGroupController::class, 'update'])->name('groups.update');
        Route::delete('groups/{id}', [CommunityGroupController::class, 'destroy'])->name('groups.destroy');
        Route::post('groups/status', [CommunityGroupController::class, 'updateStatus'])->name('groups.status');
        Route::post('groups/update-status', [CommunityGroupController::class, 'updateStatus'])->name('groups.update-status');
        
        // Post routes
        Route::get('posts', [CommunityPostController::class, 'index'])->name('posts.index');
        Route::get('posts/create', [CommunityPostController::class, 'create'])->name('posts.create');
        Route::post('posts', [CommunityPostController::class, 'store'])->name('posts.store');
        Route::get('posts/{id}', [CommunityPostController::class, 'show'])->name('posts.show');
        Route::get('posts/{id}/edit', [CommunityPostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{id}', [CommunityPostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{id}', [CommunityPostController::class, 'destroy'])->name('posts.destroy');
        Route::post('posts/status', [CommunityPostController::class, 'updateStatus'])->name('posts.status');
        
        // Member routes
        Route::get('groups/{group_id}/members', [CommunityMemberController::class, 'index'])->name('members.index');
        Route::post('groups/{group_id}/members', [CommunityMemberController::class, 'store'])->name('members.store');
        Route::put('members/{id}', [CommunityMemberController::class, 'update'])->name('members.update');
        Route::delete('members/{id}', [CommunityMemberController::class, 'destroy'])->name('members.destroy');
        Route::post('groups/{group_id}/join', [CommunityMemberController::class, 'join'])->name('members.join');
        Route::post('groups/{group_id}/leave', [CommunityMemberController::class, 'leave'])->name('members.leave');
    });
});

