<?php

use App\Http\Controllers\Api\V1\MuseumApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/museums', [MuseumApiController::class, 'index'])
        ->name('api.v1.museums.index');

    Route::get('/museums/{slug}', [MuseumApiController::class, 'show'])
        ->where('slug', '[A-Za-z0-9-]+')
        ->name('api.v1.museums.show');
});
