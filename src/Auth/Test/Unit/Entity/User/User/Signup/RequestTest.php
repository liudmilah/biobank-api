<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\Signup;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Service\Id;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\User
 *
 * @internal
 */
final class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signupRequest(
            $id = Id::generate(),
            $date = new DateTimeImmutable(),
            $email = new Email('mail@example.com'),
            $name = 'Test Name',
            $hash = 'hash',
            $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable())
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($name, $user->getName());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getSignupToken());

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals(Role::USER, $user->getRole()->getName());
    }
}
