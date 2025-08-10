<?php

namespace App\Interfaces;

interface HashInterface
{
    public function make(string $value): string;
    public function check(string $value, string $hashedValue): bool;
}
