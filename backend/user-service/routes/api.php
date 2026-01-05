<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\AuthValidationController;


Route::prefix('users')->group(function () {

    Route::get('/', [UserController::class, 'index']);              // Get all users
    Route::get('{guid}', [UserController::class, 'show']);           // Get single user

    Route::post('/', [UserController::class, 'store']);              // Create user
    Route::put('{guid}', [UserController::class, 'update']);         // Update user

    Route::patch('{guid}/password', [UserController::class, 'changePassword']); // Change password
    Route::patch('{guid}/status', [UserController::class, 'updateStatus']);     // Block / activate

    Route::delete('{guid}', [UserController::class, 'destroy']);     // Soft delete
});

Route::prefix('internal/auth')->group(function () {
    Route::post('validate', [AuthValidationController::class, 'validateCredentials']);
    Route::post('validate-guid', [AuthValidationController::class, 'validateGuid']);
});
