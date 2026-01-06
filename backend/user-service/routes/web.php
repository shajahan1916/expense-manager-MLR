<?php

use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\DashboardController;

Route::middleware('web')->group(function () {

    Route::get('/login', [LoginController::class, 'showLogin']);
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

});

