<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\TokenServiceInterface;
use App\Interfaces\HashInterface;
use App\Exceptions\AuthenticationException;


class AuthenticationService {
    protected $userRepository;
    protected $hasher;
    protected $tokenService;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param HashInterface $hasher
     * @param TokenServiceInterface $tokenService
     */
    public function __construct(UserRepositoryInterface $userRepository, HashInterface $hasher, TokenServiceInterface $tokenService) {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->tokenService = $tokenService;
    }

    /**
     * @param array $data
     * @param string $defaultRole
     * @return array
     */
    public function register(array $data, string $defaultRole = "user"): array {
        $data["password"] = $this->hasher->make(value: $data["password"]);

        $user = $this->userRepository->create($data);

        $user->assignRole($defaultRole);

        $token = $this->tokenService->createToken($user, "access_token");

        return [
            "user" => $user,
            "access_token" => $token,
            "token_type" => "Bearer"
        ];
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array {
        $user = $this->userRepository->findByEmail($credentials["email"]);

        if (!$user || !$this->hasher->check($credentials['password'], $user->password)) {
            throw new AuthenticationException();
        }

        $this->tokenService->revokeAllTokens($user);

        $token = $this->tokenService->createToken($user, 'access_token');

        return [
            'user' => $user->load('roles'),
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }
}
