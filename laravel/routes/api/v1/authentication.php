<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthenticationController;

Route::prefix("auth")
    ->controller(AuthenticationController::class)
    ->group(function () {
        Route::post("/login", "login");
        Route::post("/register", "register");
    });
