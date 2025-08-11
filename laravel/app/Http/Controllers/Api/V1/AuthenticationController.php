<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    const INVALID_CREDENTIALS = "invalid credentials";

    protected AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }

    public function register(RegisterUserRequest $request) {
        $validated = $request->validated();

        $result = $this->authenticationService->register($validated);

        return response()->json([
            "status" => "success",
            "data" => $result
        ], 201);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        try {
            $result = $this->authenticationService->login($credentials);

            return response()->json([
                "status" => "success",
                "data" => $result
            ], 200);
        } catch(ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'errors' => $e->errors(),
            ], 422);
        }

        
    }
}
