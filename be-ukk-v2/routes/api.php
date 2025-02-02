<?php

use App\Http\Controllers\Api\ManageItemController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/rent')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/items', [ManageItemController::class, 'index']);
    Route::get('/categories', [ManageItemController::class, 'getCategories']);
    Route::get('/items/{items_code}', [ManageItemController::class, 'showDetail']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('/rent', [ManageItemController::class, 'addRent']);
        Route::get('/history', [ManageItemController::class, 'getRentHistory']);
        Route::put('/rent/cancel/{id}', [ManageItemController::class, 'cancelRent']);    
    });
});