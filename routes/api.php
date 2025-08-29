<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('services', \App\Http\Controllers\Api\ServiceController::class)->except(['show']);
});

require __DIR__ . '/auth_api.php';