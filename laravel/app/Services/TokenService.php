<?php

namespace App\Services;

use App\Interfaces\TokenServiceInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenService implements TokenServiceInterface {
    
    /**
     * @param Authenticatable $user
     * @param string $name
     * @return string
    */
    public function createToken(Authenticatable $user, string $name): string {
        return $user->createToken($name)->plainTextToken;
    }

    /**
     * @param Authenticatable $user
     * @return boolean
     */
    public function revokeCurrentToken(Authenticatable $user): bool {
        return $user->currentAccessToken()->delete();
    }

    /**
     * @param Authenticatable $user
     * @return boolean
     */
    public function revokeAllTokens(Authenticatable $user): bool {
        return $user->tokens()->delete();
    }
}
