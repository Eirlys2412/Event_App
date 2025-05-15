<?php

use Illuminate\Support\Facades\Route;

// Define routes here
 
use App\Modules\Blog\Controllers\BlogController;
use App\Modules\Blog\Controllers\BlogCategoryController;
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
   
     //BlogCategory section
     Route::resource('blogcategory',  BlogCategoryController::class);
     Route::post('blogcategory_status',[ BlogCategoryController::class,'blogcatStatus'])->name('blogcategory.status');
     Route::get('blogcategory_search',[ BlogCategoryController::class,'blogcatSearch'])->name('blogcategory.search');
     //Blog section
     Route::resource('blog', BlogController::class);
     Route::post('blog_status',[ BlogController::class,'blogStatus'])->name('blog.status');
     Route::get('blog_search',[ BlogController::class,'blogSearch'])->name('blog.search');
     Route::get('category/{slug}',[BlogController::class,'categoryView'])->name('front.category.view');
     Route::post('/admin/upload/blog-photo', [\App\Modules\Blog\Controllers\UploadController::class, 'uploadBlogPhoto'])->name('admin.upload.blogphoto');
     Route::post('/upload/avatar', [\App\Modules\Blog\Controllers\UploadController::class, 'upload'])->name('admin.upload.avatar');

});


 