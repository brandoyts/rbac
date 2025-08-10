<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\DTO\TokenDTO;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\TokenServiceInterface;
use App\Interfaces\HashInterface;
use Illuminate\Validation\ValidationException;
use App\Exceptions\AuthenticationException;


class AuthenticationService {
    protected $userRepository;
    protected $hasher;
    protected $tokenService;

    public function __construct(UserRepositoryInterface $userRepository, HashInterface $hasher, TokenServiceInterface $tokenService) {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->tokenService = $tokenService;
    }

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

    public function logout(User $user): bool {
        return $this->tokenService->revokeCurrentToken($user);
    }
}
