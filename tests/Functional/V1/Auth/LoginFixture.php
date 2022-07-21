<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use App\Auth\Test\Builder\UserBuilder;
use App\Service\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class LoginFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id('709e6e08-2edf-401f-bbe3-145398606f51'))
            ->active()
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
