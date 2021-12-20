<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Permission\PermissionController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Product\ProductController;

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::group(['middleware' => ['auth:api', AdminMiddleware::class]], function () {
        Route::post('logout', [LoginController::class, 'logout']);
        Route::get('dashboard-details', [DashboardController::class, 'index']);
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('products', ProductController::class);
    });
});
