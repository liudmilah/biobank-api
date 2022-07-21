<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Entity\User\Email
 *
 * @internal
 */
final class EmailTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email($value = 'email@example.com');

        self::assertEquals($value, $email->getValue());
    }

    public function testCase(): void
    {
        $email = new Email('EmAil@example.com');

        self::assertEquals('email@example.com', $email->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('not-email');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('');
    }

    public function testEqualsTo(): void
    {
        $address = 'email@example.com';
        $email = new Email($address);
        self::assertTrue($email->isEqualTo(new Email($address)));
    }
}
