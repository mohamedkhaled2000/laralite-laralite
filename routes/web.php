<?php

use App\Controllers\Admin\AdminController;
use Laralite\Router\Route;

// Route::get('/admin/destroy/{id}', function () {
//     echo 'test';
// });
// Route::get('/admin/user', 'UserController@index');
// Route::any('/admin/admin', [AdminController::class,'index']);

Route::prefix('/admin',function () {
    Route::middleware('Admin',function(){
        Route::get('/',[AdminController::class,'index']);
        // Route::get('/messages','messages');
        // Route::post('/create','create');
        // Route::any('/delete','delete');
    });

//     Route::prefix('/user',function() {
//         Route::get('/','index');
//         Route::get('/messages','messages');
//         Route::post('/create','create');
//         Route::any('/delete/{id}','delete');
//     });

});