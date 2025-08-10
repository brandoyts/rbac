<?php

namespace App\Services;

use App\Interfaces\TokenServiceInterface;
use App\Models\User;


class TokenService implements TokenServiceInterface {
    public function createToken(User $user, string $name): string {
        return $user->createToken($name)->plainTextToken;
    }

    public function revokeCurrentToken(User $user): bool {
        return $user->currentAccessToken()->delete();
    }

    public function revokeAllTokens(User $user): bool {
        return $user->tokens()->delete();
    }
}
