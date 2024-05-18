<?php

namespace App\Helper;

use Firebase\JWT\JWT;

class JWTToken
{
    public static function createToken()
    {
        $key = 'example_key';
        $payload = [
            'iss' => 'coding-sports',
            'iat' => time(),
            'exp' => time() + 30 * 24 * 20
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
