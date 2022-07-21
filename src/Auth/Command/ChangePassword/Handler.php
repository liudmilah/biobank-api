<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Events\UserPasswordChanged;
use App\Auth\Service\PasswordHasher;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;
use DomainException;

final class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $hasher,
        private Flusher $flusher,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        try {
            $this->hasher->validate($command->oldPassword, $user->getPasswordHash() ?? '');
        } catch (\Exception) {
            throw new DomainException('Invalid old password.');
        }

        $user->changePassword($this->hasher->hash($command->newPassword));

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new UserPasswordChanged($user->getId()));
    }
}
