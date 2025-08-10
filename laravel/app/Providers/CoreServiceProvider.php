<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\HashInterface;
use App\Services\Core\HashService;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HashInterface::class, HashService::class);
    }
}
