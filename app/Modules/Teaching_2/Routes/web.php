<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Teaching_2\Controllers\ModuleController;
// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function() {
    // Route::resource('recommend', RecommendController::class);
    Route::resource('module', ModuleController::class);
    // Route::get('recommend', [RecommendController::class, 'index'])->name('recommend.index');
    Route::get('module_search', [ModuleController::class, 'moduleSearch'])->name('module.search');
});


