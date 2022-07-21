<?php

declare(strict_types=1);

namespace App\Auth\Fixture;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Service\Id;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class UserSignupFixture extends AbstractFixture
{
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6'; // 'password'

    public function load(ObjectManager $manager): void
    {
        $user = User::signupRequest(
            Id::generate(),
            new DateTimeImmutable('-1 hours'),
            new Email('signup-wait@example.com'),
            'Test User 2',
            self::PASSWORD_HASH,
            new Token('00000000-0000-0000-0000-200000000001', new DateTimeImmutable('+1 hours'))
        );
        $manager->persist($user);

        $user = User::signupRequest(
            Id::generate(),
            new DateTimeImmutable('-1 hours'),
            new Email('signup-expired@example.com'),
            'Test User 3',
            self::PASSWORD_HASH,
            new Token('00000000-0000-0000-0000-200000000002', new DateTimeImmutable('-1 hours'))
        );
        $manager->persist($user);

        $manager->flush();
    }
}
