<?php

namespace App\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;

interface TokenServiceInterface
{
    public function createToken(Authenticatable $user, string $name): string;
    public function revokeCurrentToken(Authenticatable $user): bool;
    public function revokeAllTokens(Authenticatable $user): bool;
}
