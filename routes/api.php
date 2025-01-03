<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Api 
Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'savenewUser']);
    
  });