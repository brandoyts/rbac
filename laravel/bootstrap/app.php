<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $apiRoutesPath = base_path('routes/api/v1');
            $apiRouteFiles = glob($apiRoutesPath . '/*.php');
            Route::middleware(['api'])
                ->prefix('api/v1')
                ->group(function () use ($apiRouteFiles) {
                    foreach ($apiRouteFiles as $routeFile) {
                        Route::group([], $routeFile);
                    }
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
