<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

Route::prefix("users")
    ->controller(UserController::class)
    ->group(function () {
        Route::get("/{id}", "show");
    });
