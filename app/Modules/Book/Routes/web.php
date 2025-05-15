<?php

use App\Http\Controllers\BookTransactionController;
use App\Modules\Book\Controllers\BookAccessController;
use Illuminate\Support\Facades\Route;
use App\Modules\Book\Controllers\BookController;
use App\Modules\Book\Controllers\BookPointController;
use App\Modules\Book\Controllers\BookTypeController;
use App\Modules\Book\Controllers\BookUserController;
use App\Modules\Book\Controllers\Frontend\BookFrontendController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Routes for Book
    Route::resource('books', BookController::class)->parameters(['books' => 'id']);
    Route::get('books/{id}', [BookController::class, 'show'])->where('id', '[0-9]+')->name('books.show');
    Route::delete('/admin/books/{bookId}/resource/{resourceId}', [BookController::class, 'removeResource'])->name('books.removeResource');
    Route::post('books_status', [BookController::class, 'bookStatus'])->name('books.status');
    Route::get('books_search', [BookController::class, 'bookSearch'])->name('books.search');

    // Routes for BookType
    Route::resource('booktypes', BookTypeController::class)->parameters(['booktypes' => 'id']);
    Route::post('booktypes_status', [BookTypeController::class, 'bookTypeStatus'])->name('booktypes.status');

    // Routes for BookPoint
    Route::resource('bookpoints', BookPointController::class);

    Route::resource('bookaccess', BookAccessController::class);

    Route::resource('bookusers', BookUserController::class);
    Route::get('bookusers/{userId}/updatePoints', [BookUserController::class, 'updatePoints'])->name('bookusers.updatePoints');

    Route::post('/booktransactions/process', [BookTransactionController::class, 'processTransaction'])
        ->name('booktransactions.process');
});

Route::prefix('front')->group(function() {
    Route::get('/book', [BookFrontendController::class, 'index'])->name('front.book.index'); 
    Route::get('/book/{id}', [BookFrontendController::class, 'show'])->name('front.book.show'); 
});
