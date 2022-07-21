<?php
declare(strict_types=1);

namespace App\Auth\Service\AuthTokenGenerator;

final class Params
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $role,
        public \DateTimeImmutable $date
    ) {
    }
}