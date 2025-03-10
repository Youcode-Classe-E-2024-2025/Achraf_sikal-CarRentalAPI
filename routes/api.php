<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('cars',CarController::class);
Route::post('register', [UserController::class, 'createUser']);
Route::post('login', [UserController::class, 'loginUser']);
