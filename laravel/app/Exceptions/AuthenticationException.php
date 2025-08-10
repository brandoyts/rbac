<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class AuthenticationException extends HttpResponseException {
    public function __construct(string $message = "invalid credentials") {
        parent::__construct(
            response()->json([
                'error' => [
                    'code' => 'AUTH_INVALID',
                    'message' => $message
                ]
            ], 401)
        );
    }
}
