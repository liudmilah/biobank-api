<?php

declare(strict_types=1);

namespace App\Auth\Query;

/**
 * @psalm-immutable
 */
final class Identity
{
    public function __construct(
        public string $id,
        public string $role,
        public string $email,
        public string $name
    ) {
    }
}
