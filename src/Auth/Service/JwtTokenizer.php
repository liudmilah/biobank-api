<?php

declare(strict_types=1);

namespace App\Auth\Service;

use Firebase\JWT\JWT as FJwt;
use Firebase\JWT\Key;

final class JwtTokenizer
{
    public function __construct(private string $secret, private string $algorithm)
    {
    }

    public function decode(string $token): object
    {
        return FJwt::decode($token, new Key($this->secret, $this->algorithm));
    }

    public function encode(array $payload): string
    {
        return FJwt::encode($payload, $this->secret, $this->algorithm);
    }
}
