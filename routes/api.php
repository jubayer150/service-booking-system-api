<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    
});

require __DIR__ . '/auth_api.php';