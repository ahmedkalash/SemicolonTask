<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::name("api.admin.")
    ->group(function () {
        // auth
        Route::post('/login', [AuthController::class, 'login'])->name('login');


        Route::group(['middleware' => ['auth:sanctum']], function () {
            // users
            Route::apiResource('users', UserController::class);
            Route::post('users/{user}/groups', [UserController::class, 'assignGroups']);

            // groups
            Route::apiResource('groups', GroupController::class);
            Route::post('groups/{group}/permissions', [GroupController::class, 'assignPermissions']);

            // permissions
            Route::get('permissions', [PermissionController::class, 'index']);
        });


    });


