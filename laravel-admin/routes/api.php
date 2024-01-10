<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('login', function(){
    return response(['Error' => 'Unauthorized'],401);
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('orders', OrderController::class)->only('index', 'show');
Route::apiResource('products', ProductController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('users',UserController::class)->middleware('auth:api');
Route::get('user', [UserController::class, 'user']);
Route::put('users/info', [UserController::class, 'updateInfo']);
Route::put('users/password', [UserController::class, 'updatePassword']);
Route::get('export', [OrderController::class, 'export']);
