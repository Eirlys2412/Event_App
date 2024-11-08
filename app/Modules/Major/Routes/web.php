<?php

use Illuminate\Support\Facades\Route;

// Define routes here
 
use App\Modules\Major\Controllers\MajorController;

Route::prefix('admin/major')->name('admin.major.')->middleware('auth')->group(function () {
    // Route for the major index (list)
    Route::get('/', [MajorController::class, 'index'])->name('index');

    // Route to create a new major
    Route::get('create', [MajorController::class, 'create'])->name('create');

    // Route to store a new major
    Route::post('store', [MajorController::class, 'store'])->name('store');

    // Route to edit an existing major
    Route::get('{id}/edit', [MajorController::class, 'edit'])->name('edit');

    // Route to update an existing major
    Route::put('{id}', [MajorController::class, 'update'])->name('update');

    // Route to delete a major
    Route::delete('{id}', [MajorController::class, 'destroy'])->name('destroy');

    // Route to update the status of a major (active/inactive)
    Route::post('status', [MajorController::class, 'majorStatus'])->name('status');
});


 