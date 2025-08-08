<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthenticationService;

class AuthenticationController extends Controller
{
    const INVALID_CREDENTIALS = "invalid credentials";

    protected AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }

    public function login(Request $request) {
        $result = $this->authenticationService->login($request->email, $request->password);

        if (!$result) {
            return response()->json(["message" => self::INVALID_CREDENTIALS], 401);
        }

        return response()->json($result, 200);
    }
}
