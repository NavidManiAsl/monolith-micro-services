<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'users'], function(){

    Route::get('/', [UserController::class, 'index']);
    Route::get('/{USER}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/', [UserController::class, 'update']);
    Route::delete('/{USER}', [UserController::class, 'destroy']);
});