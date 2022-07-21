<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ResetPassword;

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
final class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $token = $this->createToken();

        $now = new DateTimeImmutable();
        $user->resetPasswordRequest($token, $now);

        self::assertNotNull($user->getPasswordResetToken());

        $user->resetPassword($token->getValue(), $now, $hash = 'hash');

        /** @psalm-suppress DocblockTypeContradiction */
        self::assertNull($user->getPasswordResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $token = $this->createToken();

        $user->resetPasswordRequest($token, $token->getExpires());

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Uuid::uuid4()->toString(), $token->getExpires(), 'hash');
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $token = $this->createToken();

        $user->resetPasswordRequest($token, $token->getExpires());

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($token->getValue(), $token->getExpires()->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->resetPassword(Uuid::uuid4()->toString(), new DateTimeImmutable(), 'hash');
    }

    private function createToken(): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            (new DateTimeImmutable())->modify('+1 hour')
        );
    }
}
