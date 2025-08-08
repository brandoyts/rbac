<?php

use Illuminate\Support\Facades\Auth;
use App\Services\AuthenticationService;
use App\Models\User;

beforeEach(function() {
    $this->authService = new AuthenticationService();
});

test("returns true when login is successful", function() {
    $email = "test@mail.com";
    $password = "secret";
    $tokenString = "token-string";

    $expected = ['access_token' => $tokenString, 'token_type' => 'Bearer'];

    Auth::shouldReceive("attempt")
            ->once()
            ->with(["email" => $email, "password" => $password])
            ->andReturn($expected);

    $mockUser = mockery::mock(User::class);
    $mockUser->shouldReceive("createToken")
                ->once()
                ->with("access_token")
                ->andReturn((object)["plainTextToken" => $tokenString]);

    Auth::shouldReceive("user")
            ->once()
            ->andReturn($mockUser);


    $result = $this->authService->login($email, $password);

    expect($result)->toEqual($expected);
});

test('returns null when login fails', function () {
    $email = 'test@mail.com';
    $password = 'secret';

    // Mock Auth::attempt to return false
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['email' => $email, 'password' => $password])
        ->andReturn(false);

    // Auth::user should never be called on failure
    Auth::shouldReceive('user')->never();

    $result = $this->authService->login($email, $password);

    expect($result)->toBeNull();
});
