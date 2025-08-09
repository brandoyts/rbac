<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\DTO\TokenDTO;

class AuthenticationService {

    public function login(string $email, string $password) {
        $credentials = ["email" => $email, "password" => $password];

        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();

        $token = $user->createToken('access_token')->plainTextToken;

        return new TokenDTO($token);
    }
}
