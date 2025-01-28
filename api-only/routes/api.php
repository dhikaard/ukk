<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAdminController;
use App\Http\Controllers\ManageProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\OtpController;

Route::prefix('v1/rent')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/getProducts', [ManageProductController::class, 'getProducts']);
    Route::get('/getBrandForAddProduct', [ManageProductController::class, 'getBrandForAddProduct']);
    Route::get('/getCtgrForAddProduct', [ManageProductController::class, 'getCtgrForAddProduct']);

    Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [PasswordResetController::class, 'reset']);

    Route::post('otp/send', [OtpController::class, 'sendOtp']);
    Route::post('otp/verify', [OtpController::class, 'verifyOtp']);

    Route::middleware('auth:api')->group(function () {
        // Manage Admin & Role
        Route::post('/getUserAdmin', [ManageAdminController::class, 'getUserAdmin']);
        Route::get('/getUserForAddAdmin', [ManageAdminController::class, 'getUserForAddAdmin']);
        Route::post('/editUserAdmin', [ManageAdminController::class, 'editUserAdmin']);
        Route::get('/getRolePermission', [ManageAdminController::class, 'getRolePermission']);

        // Manage Products
        Route::post('/addProduct', [ManageProductController::class, 'addProduct']);
        Route::post('/editProduct', [ManageProductController::class, 'editProduct']);
        Route::post('/removeProduct', [ManageProductController::class, 'removeProduct']);
    });
});
