<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordHasher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Service\PasswordHasher
 *
 * @internal
 */
final class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertNotEmpty($hash);
        self::assertNotEquals($password, $hash);
    }

    public function testHashEmptyPassword(): void
    {
        $hasher = new PasswordHasher(16);

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('');
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        $hasher->validate($password, $hash);

        $this->expectException(InvalidArgumentException::class);
        $hasher->validate('wrong-password', $hash);
    }
}
