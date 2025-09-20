<?php

namespace App\Providers\Auth;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Repositories\Contracts\Auth\AuthRepositoryInterface;
// use App\Repositories\Contracts\ProjectRepositoryInterface;
// use App\Repositories\Contracts\SkillRepositoryInterface;
// use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Repositories\Eloquent\Auth\UserRepository;
use App\Repositories\Eloquent\Auth\AuthRepository;
// use App\Repositories\Eloquent\ProjectRepository;
// use App\Repositories\Eloquent\SkillRepository;
// use App\Repositories\Eloquent\ContactRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * قائمة بروابط الواجهات والمطالبات.
     *
     * @var array
     */
    protected $repositories = [
        AuthRepositoryInterface::class => AuthRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        // ProjectRepositoryInterface::class => ProjectRepository::class,
        // SkillRepositoryInterface::class => SkillRepository::class,
        // ContactRepositoryInterface::class => ContactRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }

        // تسجيل نمط Repository للنماذد
        $this->app->bind(
            \App\Repositories\Contracts\EloquentRepositoryInterface::class,
            \App\Repositories\Eloquent\BaseRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // يمكنك إضافة أي تهيئات إضافية هنا
    }
}