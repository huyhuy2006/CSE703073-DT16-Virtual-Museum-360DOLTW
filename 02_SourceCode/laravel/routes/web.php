<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuseumController;
use App\Http\Controllers\SceneViewerController;

Route::get('/', function () {
    return redirect()->route('museums.index', ['lang' => 'vi']);
});

Route::get('/museums', [MuseumController::class, 'index'])
    ->name('museums.index');

Route::get('/museums/{slug}', [MuseumController::class, 'show'])
    ->where('slug', '[A-Za-z0-9-]+')
    ->name('museums.show');

Route::get(
    '/tham-quan/{museumSlug}/{sceneSlug}',
    [SceneViewerController::class, 'show']
)->name('scenes.viewer');
