<?php

namespace App\Interfaces;

use App\Models\User;

interface TokenServiceInterface
{
    public function createToken(User $user, string $name): string;
    public function revokeCurrentToken(User $user): bool;
    public function revokeAllTokens(User $user): bool;
}
