<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAdminController;
use App\Http\Controllers\ManageProductController;
use App\Http\Controllers\AuthController;

Route::prefix('v1/rent')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/getProducts', [ManageProductController::class, 'getProducts']);

    Route::middleware('auth:api')->group(function () {
        // Manage Admin & Role
        Route::post('/getUserAdmin', [ManageAdminController::class, 'getUserAdmin']);
        Route::get('/getUserForAddAdmin', [ManageAdminController::class, 'getUserForAddAdmin']);
        Route::post('/addUserAdmin', [ManageAdminController::class, 'addUserAdmin']);
        Route::post('/editUserAdmin', [ManageAdminController::class, 'editUserAdmin']);
        Route::get('/getRolePermission', [ManageAdminController::class, 'getRolePermission']);

        // Manage Products
        Route::post('/addProduct', [ManageProductController::class, 'addProduct']);
        Route::get('/getBrandForAddProduct', [ManageProductController::class, 'getBrandForAddProduct']);
        Route::get('/getCtgrForAddProduct', [ManageProductController::class, 'getCtgrForAddProduct']);
    });
});
