<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeName;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Events\UserNameChanged;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;

final class Handler
{
    public function __construct(
        private UserRepository $users,
        private Flusher $flusher,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));
        $user->changeName($command->name);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new UserNameChanged($user->getId(), $user->getName()));
    }
}
