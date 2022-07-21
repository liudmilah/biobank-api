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
use Ramsey\Uuid\Uuid;

final class UserFixture extends AbstractFixture
{
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6'; // 'password'

    public function load(ObjectManager $manager): void
    {
        $user = User::signupRequest(
            new Id('709e6e08-2edf-401f-bbe3-145398606f51'),
            $date = new DateTimeImmutable('-30 days'),
            new Email('user@example.com'),
            'Test User 1',
            self::PASSWORD_HASH,
            new Token($value = Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $user->signupConfirm($value, $date);

        $manager->persist($user);

        $manager->flush();
    }
}
