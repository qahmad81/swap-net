<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NetworkController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/social-login', [AuthController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::get('/networks', [NetworkController::class, 'index']);
    Route::post('/networks', [NetworkController::class, 'store']);
    Route::get('/networks/{network}', [NetworkController::class, 'show']);
    Route::post('/networks/join/{invite_code}', [NetworkController::class, 'join']);
    Route::delete('/networks/{network}/leave', [NetworkController::class, 'leave']);
    Route::get('/networks/{network}/members', [NetworkController::class, 'members']);
});
