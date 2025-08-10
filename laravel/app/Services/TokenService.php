<?php

namespace App\Services;

use App\Interfaces\TokenServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

// handles sanctum token
class TokenService implements TokenServiceInterface {

    public function createToken(Authenticatable $user, string $name): string {
        return $user->createToken($name)->plainTextToken;
    }

    public function revokeCurrentToken(Authenticatable $user): bool {
        return $user->currentAccessToken()->delete();
    }

    public function revokeAllTokens(Authenticatable $user): bool {
        return $user->tokens()->delete();
    }
}
