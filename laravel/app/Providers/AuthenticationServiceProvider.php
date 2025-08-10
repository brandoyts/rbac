<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TokenServiceInterface;
use App\Services\TokenService;
use App\Services\AuthenticationService;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\HashInterface;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthenticationService::class, function ($app) {
            return new AuthenticationService(
                $app->make(UserRepositoryInterface::class),
                $app->make(HashInterface::class),
                $app->make(TokenServiceInterface::class)
            );
        });
    }
}
