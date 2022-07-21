<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Auth\Entity\User\Email;
use App\Auth\Test\Builder\UserBuilder;
use App\Service\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class AuthUserFixture extends AbstractFixture
{
    public const ID = '709e6e08-2edf-401f-bbe3-145398606f51';
    public const EMAIL = 'user@test.com';

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id(self::ID))
            ->withEmail(new Email(self::EMAIL))
            ->active()
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
