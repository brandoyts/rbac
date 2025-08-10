<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserService {
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function findUserById(int|string $id): ?User {
         return $this->repository->findById($id);
    }

    public function findByEmail(string $email): ?User {
        return $this->repository->findByEmail($email);
    }

    public function createUser(array $fields): ?User {
        return $this->repository->create($fields);
    }
}
