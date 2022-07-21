<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Signup;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Service\Id;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ConfirmFixture extends AbstractFixture
{
    public const VALID = '709e6e08-2edf-401f-bbe3-145398606f51';
    public const EXPIRED = '709e6e08-2edf-401f-bbe3-145398606f52';

    public function load(ObjectManager $manager): void
    {
        $user = User::signupRequest(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('valid@example.com'),
            'name',
            'password-hash',
            new Token(self::VALID, $date->modify('+1 hour'))
        );
        $manager->persist($user);

        $user = User::signupRequest(
            Id::generate(),
            $date = new DateTimeImmutable(),
            new Email('expired@example.com'),
            'name',
            'password-hash',
            new Token(self::EXPIRED, $date->modify('-2 hours'))
        );
        $manager->persist($user);

        $manager->flush();
    }
}
