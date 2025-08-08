<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use App\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerUserService();
    }

    public function registerUserService() {
        // Bind interface to concrete repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Bind service with repository injected
        $this->app->bind(UserService::class, function($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
