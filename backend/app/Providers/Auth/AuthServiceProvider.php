<?php

namespace App\Providers\Auth;

use App\Models\User;
use App\Policies\Auth\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // تكوين Sanctum
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            if (!$isValid) return false;
            
            // التحقق من حالة المستخدم
            $user = $accessToken->tokenable;
            return $user && $user->status === 'active';
        });

        // تعريف الـ Gates للصلاحيات
        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-content', function (User $user) {
            return $user->hasRole('admin');
        });

        // سياسات الصلاحيات المتقدمة
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
        
        // تعريف الـ Gates الإضافية إذا لزم الأمر
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}