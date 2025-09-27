<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post(uri: '');

Route::name("api.admin.")
    ->group(function () {
        Route::post('/login', [AuthController::class,'login'])->name('login');



        Route::group(['middleware'=>['auth:sanctum']],function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
        });


});


