<?php

namespace App\Services\Core;

use App\Interfaces\HashInterface;
use Illuminate\Contracts\Hashing\Hasher;


class HashService implements HashInterface
{
    private $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function make(string $value): string
    {
        return $this->hasher->make($value);
    }

    public function check(string $value, string $hashedValue): bool
    {
        return $this->hasher->check($value, $hashedValue);
    }
}
