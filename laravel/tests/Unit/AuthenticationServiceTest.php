<?php

use Illuminate\Support\Facades\Auth;
use App\Services\AuthenticationService;
use App\Models\User;
use App\Models\Role;
use App\Services\AuthService;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\HashInterface;
use App\Interfaces\TokenServiceInterface;

beforeEach(function() {
    $this->mockUserRepo = mock(UserRepositoryInterface::class);
    $this->mockHash = mock(HashInterface::class);
    $this->mockTokenService = mock(TokenServiceInterface::class);
    $this->authService = new AuthenticationService($this->mockUserRepo, $this->mockHash, $this->mockTokenService);
});

test("registers successfully and creates access token", function() {

    $userData = [
        "name" => "tester",
        "email" => "tester@mail.com",
        "password" => "secret123",
    ];

    $hashedPassword = "hashed-password";
    $token = "generated_token";

    $this->mockHash->shouldReceive("make")
    ->once()
    ->with($userData["password"])
    ->andReturn($hashedPassword);

    $mockUser = User::factory()->make([
        "id" => 1,
        "name" => $userData["name"],
        "email" => $userData["email"],
        "password" => $hashedPassword,
    ]);


    $this->mockUserRepo->shouldReceive("create")
        ->once()
        ->with([
            "name" => $userData["name"],
            "email" => $userData["email"],
            "password" => $hashedPassword,
        ])
        ->andReturn($mockUser);

    $this->mockTokenService->shouldReceive("createToken")
        ->once()
        ->with($mockUser, "access_token")
        ->andReturn($token);

    $result = $this->authService->register($userData);

    expect($result)->toBeArray();
    expect($result)->toHaveKeys(['user', 'access_token']);
    expect($result['user'])->toBe($mockUser);
    expect($result['access_token'])->toBe($token);
});

test("logins successfully and creates access token", function() {
    $loginInput = [
        "email" => "test@mail.com",
        "password" => "secret123"
    ];

    $hashedPassword = "hashed-password";
    $token = "generated-token";

    $mockUser = User::factory()->make([
        "id" => 1,
        "name" => "tester",
        "email" => "test@mail.com",
        "password" => $hashedPassword,
    ]);

    $this->mockUserRepo->shouldReceive("findByEmail")
        ->once()
        ->with($loginInput["email"])
        ->andReturn($mockUser);


    $this->mockHash->shouldReceive("check")
        ->once()
        ->with($loginInput["password"], $mockUser["password"])
        ->andReturn(true);


    $this->mockTokenService->shouldReceive("revokeAllTokens")
        ->once()
        ->with($mockUser)
        ->andReturn(true);


    $this->mockTokenService->shouldReceive("createToken")
        ->once()
        ->with($mockUser, "access_token")
        ->andReturn($token);

    $result = $this->authService->login($loginInput);

    expect($result)->toBeArray();
    expect($result)->toHaveKeys(['user', 'access_token']);
});
