<?php

use App\Modules\Group\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

// Define routes here
 
Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
   
     ///Blog section
     Route::resource('group', GroupController::class);
 

});


 