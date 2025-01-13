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
    Route::post('student', [\App\Http\Controllers\Api\AuthenticationController::class, 'createStudent'])->middleware('auth:api');
    Route::post('teacher', [\App\Http\Controllers\Api\AuthenticationController::class, 'createTeacher'])->middleware('auth:api');

    //UniverInfo
    Route::get('/nganhs', [\App\Http\Controllers\Api\UniverInfoController::class, 'getNganhs']);
    Route::get('/donvi', [\App\Http\Controllers\Api\UniverInfoController::class, 'getDonVis']);
    Route::get('/chuyenNganh', [\App\Http\Controllers\Api\UniverInfoController::class, 'chuyenNganhs']);

     
    //Profile
    Route::post('updateprofile', [\App\Http\Controllers\Api\ApiUserController::class, 'updateProfile'])->middleware('auth:api');
    Route::get('profile', [\App\Http\Controllers\Api\ApiUserController::class, 'viewProfile'])->middleware('auth:api');
    Route::post('upload-photo', [\App\Http\Controllers\Api\ApiUserController::class, 'uploadPhoto'])->middleware('auth:api');


    //Student
    Route::get('/student/{userId}', [\App\Http\Controllers\Api\StudentController::class, 'show'])->middleware('auth:api');
    Route::put('/student/{userId}', [\App\Http\Controllers\Api\StudentController::class, 'update'])->middleware('auth:api');


    //Teacher
    Route::get('/teacher/{userId}', [\App\Http\Controllers\Api\TeacherController::class, 'show'])->middleware('auth:api');
    Route::put('/teacher/{userId}', [\App\Http\Controllers\Api\TeacherController::class, 'update'])->middleware('auth:api');

  });