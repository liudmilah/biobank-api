<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\ResetPassword;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ConfirmFixture extends AbstractFixture
{
    public const VALID = '709e6e08-2edf-401f-bbe3-145398606f51';
    public const EXPIRED = '709e6e08-2edf-401f-bbe3-145398606f52';

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->active()
            ->withEmail(new Email('valid@test.com'))
            ->build();
        $now = new DateTimeImmutable();
        $user->resetPasswordRequest(new Token(self::VALID, $now->modify('+1 hour')), $now);
        $manager->persist($user);

        $user = (new UserBuilder())
            ->active()
            ->withEmail(new Email('expired@test.com'))
            ->build();
        $now = new DateTimeImmutable();
        $user->resetPasswordRequest(new Token(self::EXPIRED, $now->modify('-2 hours')), $now);
        $manager->persist($user);

        $manager->flush();
    }
}
