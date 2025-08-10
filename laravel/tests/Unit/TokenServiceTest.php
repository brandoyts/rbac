<?php

use App\Services\TokenService;
use App\Models\User;

beforeEach(function() {
    $this->mockUser = mock(User::class);
    $this->service = new TokenService();
});

test("successfully create a new token", function(){
    $token = "generated-token";
    $tokenName = "access_token";

    $this->mockUser->shouldReceive("createToken")
        ->once()
        ->with($tokenName)
        ->andReturn((object)["plainTextToken" => $token]);


    $result = $this->service->createToken($this->mockUser, $tokenName);

    expect($result)->toBe($token);
});

test("successfully revoke current token", function() {
    $mockTokenDelete = mock();
    $mockTokenDelete->shouldReceive("delete")
        ->once()
        ->andReturn(true);

    $this->mockUser
        ->shouldReceive("currentAccessToken")
        ->once()
        ->andReturn($mockTokenDelete);

    $result = $this->service->revokeCurrentToken($this->mockUser);

    expect($result)->toBeTrue();
});


test("successfully revoke all tokens", function () {
    $mockTokenDelete = mock();
    $mockTokenDelete->shouldReceive("delete")
        ->once()
        ->andReturn(true);

    $this->mockUser
        ->shouldReceive("tokens")
        ->once()
        ->andReturn($mockTokenDelete);

    $result = $this->service->revokeAllTokens($this->mockUser);

    expect($result)->toBeTrue();
});
