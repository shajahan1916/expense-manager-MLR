<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (NO JWT, STRICT THROTTLE)
|--------------------------------------------------------------------------
| These routes are used for:
| - login
| - splash login
| - logout
| JWT is NOT required here.
| Throttling is strict to prevent brute-force attacks.
*/
Route::prefix('auth')->group(function () {

    Route::middleware(['throttle:5,1'])->any('{path?}', function ($path = null) {
        return app(GatewayController::class)
            ->handle(request(), 'auth', $path);
    })->where('path', '.*');

});


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (JWT + NORMAL THROTTLE)
|--------------------------------------------------------------------------
| These routes require:
| - Valid JWT token
| - Normal rate limiting
| JWT verification is handled by verify.jwt middleware.
*/
Route::middleware(['verify.jwt', 'throttle:60,1'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER SERVICE ROUTES
    |--------------------------------------------------------------------------
    | All /users/* requests are forwarded to user-service.
    */
    Route::prefix('users')->group(function () {

        Route::any('{path?}', function ($path = null) {
            return app(GatewayController::class)
                ->handle(request(), 'users', $path);
        })->where('path', '.*');

    });

});
