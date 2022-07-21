<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\ResetPassword;

use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
