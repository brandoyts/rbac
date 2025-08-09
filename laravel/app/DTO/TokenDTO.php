<?php

namespace App\DTO;


class TokenDTO {
    public string $accessToken;
    public string $tokenType;

    public function __construct(string $accessToken, string $tokenType = "Bearer") {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
    }
}
