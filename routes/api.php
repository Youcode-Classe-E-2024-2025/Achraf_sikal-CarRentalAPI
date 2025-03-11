<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;

Route::apiResource('cars',CarController::class);
Route::post('register', [UserController::class, 'createUser']);
Route::post('login', [UserController::class, 'loginUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('rent', [RentalController::class, 'rentCar']);
    Route::post('pay', [PaymentController::class, 'buyCar']);
});
