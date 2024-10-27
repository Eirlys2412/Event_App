<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Recommend\Controllers\RecommendController;
// Define routes here
Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function() {
    // Route::resource('recommend', RecommendController::class);
    Route::resource('recommend', RecommendController::class);
    // Route::get('recommend', [RecommendController::class, 'index'])->name('recommend.index');
    Route::get('recommend_search', [RecommendController::class, 'recommendSearch'])->name('recommend.search');
});


