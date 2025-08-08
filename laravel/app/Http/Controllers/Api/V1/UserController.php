<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show(string $id) {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json(["message" => "user not found"], 200);
        }

        return response()->json($user);
    }
}
