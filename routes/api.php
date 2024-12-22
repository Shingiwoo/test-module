<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\User\DashboardUserController;
use App\Http\Controllers\Api\Admin\DashboardAdminController;


//route login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function() {
    //logout
    Route::post('/logout', [AuthController::class, 'logout']);

});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/dashboard', DashboardAdminController::class);

        //permissions
        Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [PermissionController::class, 'all'])
        ->middleware('permission:permissions.index');
    });
});

//group route with prefix "user"
Route::prefix('user')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/user/dashboard', DashboardUserController::class);
    });
});
