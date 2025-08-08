<?php

use App\Services\AuthenticationService;
use App\Http\Controllers\Api\V1\AuthenticationController;

it('returns token on successful login', function () {
    $email = 'test@mail.com';
    $password = 'secret';

    $mockResult = [
        'access_token' => 'dummy-token',
        'token_type' => 'Bearer',
    ];

    // Mock AuthenticationService and bind it to the container
    $this->mock(AuthenticationService::class, function ($mock) use ($email, $password, $mockResult) {
        $mock->shouldReceive('login')
            ->once()
            ->with($email, $password)
            ->andReturn($mockResult);
    });

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
             ->assertJson($mockResult);
});

it('returns 401 on failed login with proper message', function () {
    $email = 'test@mail.com';
    $password = 'wrong-password';

    $this->mock(AuthenticationService::class, function ($mock) use ($email, $password) {
        $mock->shouldReceive('login')
            ->once()
            ->with($email, $password)
            ->andReturn(null);
    });

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $email,
        'password' => $password,
    ]);

    // Use the constant from the controller class here
    $expectedMessage = AuthenticationController::INVALID_CREDENTIALS;

    $response->assertStatus(401)
             ->assertJson([
                 'message' => $expectedMessage,
             ]);
});
