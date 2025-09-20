<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\UserController;

// Public routes
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/{id}', [ProjectController::class, 'show'])
    ->name('projects.show')
    ->where('id', '[0-9]+');

Route::get('/projects/slug/{slug}', [ProjectController::class, 'showBySlug'])
    ->name('projects.showBySlug');

Route::get('/skills', [SkillController::class, 'index'])
    ->name('skills.index');

Route::get('/skills/{id}', [SkillController::class, 'show'])
    ->name('skills.show')
    ->where('id', '[0-9]+');

Route::get('/skills/category/{category}', [SkillController::class, 'byCategory'])
    ->name('skills.byCategory');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');

// Authenticated routes
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // User profile routes
    Route::get('/profile', [UserController::class, 'profile'])
        ->name('user.profile');
    
    Route::put('/profile', [UserController::class, 'updateProfile'])
        ->name('user.profile.update');
    
    Route::delete('/account', [UserController::class, 'deleteAccount'])
        ->name('user.account.delete');

    // Admin only routes
    Route::middleware(['can:admin'])->group(function () {
        // Projects admin routes
        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');
        
        Route::put('/projects/{id}', [ProjectController::class, 'update'])
            ->name('projects.update')
            ->where('id', '[0-9]+');
        
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])
            ->name('projects.destroy')
            ->where('id', '[0-9]+');
        
        // Skills admin routes
        Route::post('/skills', [SkillController::class, 'store'])
            ->name('skills.store');
        
        Route::put('/skills/{id}', [SkillController::class, 'update'])
            ->name('skills.update')
            ->where('id', '[0-9]+');
        
        Route::delete('/skills/{id}', [SkillController::class, 'destroy'])
            ->name('skills.destroy')
            ->where('id', '[0-9]+');
        
        // Contacts admin routes
        Route::get('/contacts', [ContactController::class, 'index'])
            ->name('contacts.index');
        
        Route::get('/contacts/stats', [ContactController::class, 'stats'])
            ->name('contacts.stats');
        
        Route::get('/contacts/{id}', [ContactController::class, 'show'])
            ->name('contacts.show')
            ->where('id', '[0-9]+');
        
        Route::put('/contacts/{id}/read', [ContactController::class, 'markAsRead'])
            ->name('contacts.markAsRead')
            ->where('id', '[0-9]+');
        
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])
            ->name('contacts.destroy')
            ->where('id', '[0-9]+');

        // User management routes
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
        
        Route::post('/users/{user}/block', [UserController::class, 'blockUser'])
            ->name('users.block');
        
        Route::post('/users/{user}/unblock', [UserController::class, 'unblockUser'])
            ->name('users.unblock');
    });
});