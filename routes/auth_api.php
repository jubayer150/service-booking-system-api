<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;


Route::middleware(['auth.sanctum'])->group(function () {
    Route::post('logout', [\App\Http\Controllers\Api\Auth\AuthenticateController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {
    Route::post('register', \App\Http\Controllers\Api\Auth\RegistrationController::class);
    Route::post('login', [\App\Http\Controllers\Api\Auth\AuthenticateController::class, 'store']);
});