<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAdminController;
use App\Http\Controllers\AuthController;

Route::prefix('v1/rent')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth')->group(function () {
        Route::post('/addAdmin', [ManageAdminController::class, 'add']);
        Route::post('/editAdmin', [ManageAdminController::class, 'edit']);
    });
});
