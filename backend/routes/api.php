<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ContactController;

// Routes without authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);
Route::get('/projects/slug/{slug}', [ProjectController::class, 'showBySlug']);

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{id}', [SkillController::class, 'show']);
Route::get('/skills/category/{category}', [SkillController::class, 'byCategory']);

Route::post('/contact', [ContactController::class, 'store']);

// Routes with authentication
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Admin only routes - Using Policies for authorization instead of role middleware
    Route::middleware(['can:admin'])->group(function () {
        // Projects admin routes
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
        
        // Skills admin routes
        Route::post('/skills', [SkillController::class, 'store']);
        Route::put('/skills/{id}', [SkillController::class, 'update']);
        Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
        
        // Contacts admin routes
        Route::get('/contacts', [ContactController::class, 'index']);
        Route::get('/contacts/stats', [ContactController::class, 'stats']);
        Route::get('/contacts/{id}', [ContactController::class, 'show']);
        Route::put('/contacts/{id}/read', [ContactController::class, 'markAsRead']);
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
    });
});