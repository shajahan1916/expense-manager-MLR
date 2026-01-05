<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (NO JWT, STRICT THROTTLE)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::middleware('throttle:5,1')->any('{path?}', function ($path = null) {
        return app(GatewayController::class)
            ->handle(request(), 'auth', $path);
    })->where('path', '.*');

});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (JWT + NORMAL THROTTLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['jwt.auth', 'throttle:60,1'])->group(function () {

    Route::prefix('users')->group(function () {

        Route::any('{path?}', function ($path = null) {
            return app(GatewayController::class)
                ->handle(request(), 'users', $path);
        })->where('path', '.*');

    });

});

