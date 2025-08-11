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

    $mockUser = Mockery::mock(User::class)->makePartial();
    $mockUser->shouldIgnoreMissing();

    $mockUser->id = 1;
    $mockUser->name = $userData["name"];
    $mockUser->email = $userData["email"];
    $mockUser->password = $hashedPassword;

    // Stub role-related methods
    $mockUser->shouldReceive('roles')->andReturn(collect());
    $mockUser->shouldReceive('hasRole')->andReturn(true);
    $mockUser->shouldReceive('hasPermission')->andReturn(true);
    $mockUser->shouldReceive('assignRole')->andReturnSelf();
    $mockUser->shouldReceive('removeRole')->andReturnSelf();


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

test("logins successfully and creates access token", function () {
    // Arrange
    $loginInput = [
        "email" => "test@mail.com",
        "password" => "secret123"
    ];

    $mockUser = mock(User::class)->makePartial();
    $mockUser->id = 1;
    $mockUser->name = "tester";
    $mockUser->email = $loginInput["email"];
    $mockUser->password = "hashed-password";

    $mockUser->shouldReceive('load')
        ->once()
        ->with('roles')
        ->andReturnSelf();

    $this->mockUserRepo->shouldReceive("findByEmail")
        ->once()
        ->with($loginInput["email"])
        ->andReturn($mockUser);

    $this->mockHash->shouldReceive("check")
        ->once()
        ->with($loginInput["password"], $mockUser->password)
        ->andReturn(true);

    $this->mockTokenService->shouldReceive("revokeAllTokens")
        ->once()
        ->with($mockUser)
        ->andReturn(true);

    $this->mockTokenService->shouldReceive("createToken")
        ->once()
        ->with($mockUser, "access_token")
        ->andReturn("generated-token");

    // Act
    $result = $this->authService->login($loginInput);

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['user', 'access_token', 'token_type'])
        ->and($result['access_token'])->toBe("generated-token")
        ->and($result['user'])->toBe($mockUser);
});


