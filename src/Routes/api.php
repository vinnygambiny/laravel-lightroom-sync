<?php

use Illuminate\Support\Facades\Route;
use VinnyGambiny\LightroomSync\Http\Controllers\AlbumController;
use VinnyGambiny\LightroomSync\Http\Controllers\AuthController;
use VinnyGambiny\LightroomSync\Http\Controllers\ConfigController;
use VinnyGambiny\LightroomSync\Http\Controllers\ContentController;

Route::prefix('api/lightroomsync')->group(function () {
    Route::post('/auth', [AuthController::class, 'index']);

    Route::middleware(config('lightroom-sync.middleware'))->group(function () {
        Route::get('/config', [ConfigController::class, 'index']);

        Route::get('/albums/{album_id}', [AlbumController::class, 'show']);
        Route::post('/albums', [AlbumController::class, 'store']);
        Route::put('/albums/{album_id}', [AlbumController::class, 'update']);
        Route::delete('/albums/{album_id}', [AlbumController::class, 'destroy']);

        Route::get('/content/{content_id}', [ContentController::class, 'show']);
        Route::post('/content', [ContentController::class, 'store']);
        Route::put('/content/{content_id}', [ContentController::class, 'update']);
        Route::delete('/content/{media}', [ContentController::class, 'destroy']);
    });
});
