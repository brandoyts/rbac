<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;


class UserRepository implements UserRepositoryInterface
{
    public function findOne(array $criteria): ?User
    {
        return User::where($criteria)->first();
    }

    public function findById(int|string $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
