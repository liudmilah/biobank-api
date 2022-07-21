<?php

declare(strict_types=1);

namespace App\Auth\Command\Login;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Events\UserLoggedIn;
use App\Auth\Service\PasswordHasher;
use App\Event\EventDispatcher;
use DomainException;

final class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $passwordHasher,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function handle(Command $command)
    {
        try {
            $user = $this->users->getActiveByEmail(new Email($command->email));

            $this->passwordHasher->validate($command->password, $user->getPasswordHash() ?? '');

        } catch (\Throwable) {
            throw new DomainException('Invalid email or password.');
        }

        $this->eventDispatcher->dispatch(new UserLoggedIn($user->getId()));
    }
}
