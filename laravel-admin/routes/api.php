<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;


Route::get('login', function () {
    return response(['Error' => 'Unauthorized'], 401);
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('orders', OrderController::class)->only('index', 'show');
    Route::apiResource('products', ProductController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::get('user', [UserController::class, 'user']);
    Route::put('users/info', [UserController::class, 'updateInfo']);
    Route::put('users/password', [UserController::class, 'updatePassword']);
    Route::get('export', [OrderController::class, 'export']);
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::get('chart', [DashboardController::class, 'chart']);

});
