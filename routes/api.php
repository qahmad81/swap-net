<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\NetworkController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/auth/social-login', [AuthController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::apiResource('networks', NetworkController::class);
    Route::post('/networks/join/{invite_code}', [NetworkController::class, 'join']);
    Route::delete('/networks/{network}/leave', [NetworkController::class, 'leave']);
    Route::get('/networks/{network}/members', [NetworkController::class, 'members']);

    Route::apiResource('listings', ListingController::class);
    Route::post('/listings/{listing}/close', [ListingController::class, 'close']);
    Route::post('/listings/{listing}/renew', [ListingController::class, 'renew']);

    Route::apiResource('offers', OfferController::class)->except(['update']);
    Route::post('/offers/{offer}/accept', [OfferController::class, 'accept']);
    Route::post('/offers/{offer}/reject', [OfferController::class, 'reject']);
    Route::post('/offers/{offer}/withdraw', [OfferController::class, 'withdraw']);

    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('/messages/{id}/read', [MessageController::class, 'markRead']);

    Route::post('/deliveries', [DeliveryController::class, 'store']);
    Route::get('/deliveries/{id}', [DeliveryController::class, 'show']);
    Route::put('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus']);

    Route::get('/users/{user_id}/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});
