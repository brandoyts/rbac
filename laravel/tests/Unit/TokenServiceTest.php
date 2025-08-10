<?php

use Mockery;
use App\Interfaces\UserRepositoryInterface;
use App\Services\TokenService;
use App\Models\User;

beforeEach(function() {
    $this->mockUser = User::factory()->make([
        "id" => 1,
        "name" => "user1",
        "email" => "user@mail.com",
        "password" => "secret123",
    ]);

    $this->mockUserRepository = Mockery::mock(UserRepositoryInterface::class);
    $this->service = new TokenService($this->mockUserRepository);
});

afterEach(function() {
    Mockery::close();
});

test("successfully create a new token", function(){
    $token = "generated-token";
    $tokenName = "access_token";

    $mockUser = User::factory()->make([
        "id" => 1,
        "name" => "user1",
        "email" => "user@mail.com",
        "password" => "secret123",
    ]);

    $this->mockUserRepository->shouldReceive("createToken")
        ->once()
        ->with($tokenName)
        ->andReturn((object)["plainTextToken" => $token]);


    $result = $this->service->createToken($mockUser, $tokenName);

    expect($result)->toBe($token);
});

test("successfully revoke current token", function() {
    $mockToken = Mockery::mock();
    $mockToken->shouldReceive("delete")
        ->once()
        ->andReturn(true);

    $this->mockUserRepository
        ->shouldReceive("currentAccessToken")
        ->once()
        ->andReturn($mockToken);

    $result = $this->service->revokeCurrentToken($this->mockUser);

    expect($result)->toBeTrue();
});


test("successfully revoke all tokens", function () {
    $mockTokens = Mockery::mock();
    $mockTokens->shouldReceive("delete")
        ->once()
        ->andReturn(true);

    $this->mockUserRepository
        ->shouldReceive("tokens")
        ->once()
        ->andReturn($mockTokens);

    $result = $this->service->revokeAllTokens($this->mockUser);

    expect($result)->toBeTrue();
});
