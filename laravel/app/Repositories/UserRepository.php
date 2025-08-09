<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;


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

    public function create(array $fields): ?User {
        DB::beginTransaction();

        try {
            $user = User::create($fields);
            DB::commit();

            return $user;

        } catch (\Exception $e) {
              \Log::error('User creation failed: ' . $e->getMessage());
            DB::rollback();
            return null;
        }
    }
}
