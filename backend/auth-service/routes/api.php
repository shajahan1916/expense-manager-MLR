<?php

use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('splash-login', [AuthController::class, 'splashLogin']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::get('jwt-debug', function () {
    return [
        'ttl' => config('jwt.ttl'),
        'refresh_ttl' => config('jwt.refresh_ttl'),
        'grace' => config('jwt.blacklist_grace_period'),
        'leeway' => config('jwt.leeway'),
        'types' => [
            gettype(config('jwt.ttl')),
            gettype(config('jwt.refresh_ttl')),
            gettype(config('jwt.blacklist_grace_period')),
            gettype(config('jwt.leeway')),
        ]
    ];
});
