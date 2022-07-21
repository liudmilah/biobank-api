<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\Signup;

use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Auth\Entity\User\User
 *
 * @internal
 */
final class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withSignupToken($token = $this->createToken())
            ->build();

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->signupConfirm(
            $token->getValue(),
            $token->getExpires()->modify('-1 day')
        );

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getSignupToken());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())
            ->withSignupToken($token = $this->createToken())
            ->build();

        $this->expectExceptionMessage('Token is invalid.');

        $user->signupConfirm(
            Uuid::uuid4()->toString(),
            $token->getExpires()
        );
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())
            ->withSignupToken($token = $this->createToken())
            ->build();

        $this->expectExceptionMessage('Token is expired.');

        $user->signupConfirm(
            $token->getValue(),
            $token->getExpires()->modify('+1 day')
        );
    }

    public function testTokenAlreadyConfirmed(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withSignupToken($token)
            ->active()
            ->build();

        $this->expectExceptionMessage('Confirmation is not required.');

        $user->signupConfirm(
            $token->getValue(),
            $token->getExpires()
        );
    }

    private function createToken(): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+1 day')
        );
    }
}
