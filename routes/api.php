<?php

use App\Http\Controllers\API\AuthController;

Route::post('api/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('users',[AuthController::class, 'getUsers']);
