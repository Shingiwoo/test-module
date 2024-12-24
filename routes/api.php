<?php

use Illuminate\Support\Facades\Route;


//route login
Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function() {
    //logout
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\DashboardAdminController::class]);

        //permissions
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])
        ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
        ->middleware('permission:permissions.index');

        //roles all
        Route::get('/roles/all', [\App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
        ->middleware('permission:roles.index');

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)
        ->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //Posts
        Route::apiResource('/tasks', App\Http\Controllers\Api\Admin\TaskController::class)
        ->middleware('permission:tasks.index|tasks.store|tasks.update|tasks.delete');
    });
});

//group route with prefix "user"
Route::prefix('user')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        //dashboard
        Route::get('/user/dashboard', [App\Http\Controllers\Api\User\DashboardUserController::class]);
    });
});
