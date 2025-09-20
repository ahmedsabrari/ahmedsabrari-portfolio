<?php

namespace App\Providers;


use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\Auth\AuthRepositoryInterface::class,
            \App\Repositories\Eloquent\Auth\AuthRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\Auth\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\Auth\UserRepository::class
        );
        
        $this->app->bind(
            \App\Http\Services\Auth\AuthService::class,
            function ($app) {
                return new \App\Http\Services\Auth\AuthService(
                    $app->make(\App\Repositories\Contracts\Auth\AuthRepositoryInterface::class)
                );
            }
        );
        
        $this->app->bind(
            \App\Http\Services\Auth\UserService::class,
            function ($app) {
                return new \App\Http\Services\Auth\UserService(
                    $app->make(\App\Repositories\Contracts\Auth\UserRepositoryInterface::class)
                );
            }
        );
    }
}