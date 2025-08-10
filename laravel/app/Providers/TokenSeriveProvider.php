<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TokenService;
use App\Interfaces\TokenServiceInterface;
use App\Interfaces\UserRepositoryInterface;

class TokenSeriveProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         $this->app->bind(TokenServiceInterface::class, function ($app) {
            return new TokenService(
                $app->make(UserRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
