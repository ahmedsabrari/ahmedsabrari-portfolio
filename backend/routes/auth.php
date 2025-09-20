<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register'])
    ->name('auth.register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('auth.login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('auth.logout');
    
    Route::post('/refresh', [AuthController::class, 'refresh'])
        ->name('auth.refresh');
    
    Route::get('/me', [AuthController::class, 'me'])
        ->name('auth.me');
});