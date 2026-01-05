<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migrations', function () {
    $outputLog = new BufferedOutput;
    // Captures the actual terminal text
    Artisan::call('migrate', ["--force" => true], $outputLog);
    Artisan::call('db:seed', ['--class' => 'UserSeeder'], $outputLog);
    
    return "<pre>" . $outputLog->fetch() . "</pre>";
});
