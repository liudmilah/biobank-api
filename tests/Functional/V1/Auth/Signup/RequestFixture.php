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
use Ramsey\Uuid\Uuid;

final class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = User::signupRequest(
            Id::generate(),
            $date = new DateTimeImmutable('-30 days'),
            new Email('existing@example.com'),
            'name',
            'password-hash',
            new Token(Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}
