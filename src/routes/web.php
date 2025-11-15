<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return ['Hello' => 'World'];
});


Route::get('/laravel-version', function () {
    return ['Laravel' => app()->version()];
});

Route::prefix('data')->group(function () {
    Route::get('/emissions', function () {
        $data = Storage::disk('local')->json('data/emissions.json');
        return $data;
    });
    Route::get('/vehicles', function () {
        $data = Storage::disk('local')->json('data/vehicles.json');
        return $data;
    });
    Route::get('/status', function () {
        $data = Storage::disk('local')->json('data/status.json');
        return $data;
    });
});

require __DIR__.'/auth.php';


