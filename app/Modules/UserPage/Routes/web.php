<?php

use Illuminate\Support\Facades\Route;
use App\Modules\UserPage\Controllers\UserPageController;

Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {

    // UserPage section
    Route::resource('userpage', UserPageController::class);
    // Route::post('userpage_status', [UserPageController::class, 'userpageStatus'])->name('userpage.status');
    // Route::get('userpage_search', [UserPageController::class, 'userpageSearch'])->name('userpage.search');

});
