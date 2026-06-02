<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::GET('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
Route::POST('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::POST('/refresh-token', [AuthController::class, 'refreshToken']);
