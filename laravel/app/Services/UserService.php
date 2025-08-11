<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserService {
    protected UserRepositoryInterface $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository) {
        $this->repository = $repository;
    }


    /**
     * @param integer|string $id
     * @return User|null
     */
    public function findUserById(int|string $id): ?User {
        return $this->repository->findById($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User {
        return $this->repository->findByEmail($email);
    }

    /**
     * @param array $fields
     * @return User|null
     */
    public function createUser(array $fields): ?User {
        return $this->repository->create($fields);
    }
}
