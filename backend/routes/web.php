<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Main web routes
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Health check route (for monitoring)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => config('app.name')
    ]);
})->name('health');

// Fallback route for SPA (if you're building a single page application)
Route::get('/{any}', [HomeController::class, 'index'])
    ->where('any', '.*')
    ->name('spa');