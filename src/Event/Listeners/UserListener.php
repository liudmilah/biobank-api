<?php

declare(strict_types=1);

namespace App\Event\Listeners;

use App\Auth\Events\UserLoggedIn;
use App\Auth\Events\UserLoggedOut;
use App\Auth\Events\UserNameChanged;
use App\Auth\Events\UserPasswordChanged;
use App\Event\EventInterface;
use App\Service\Publisher;

final class UserListener implements \App\Event\ListenerInterface
{
    public function __construct(private Publisher $publisher)
    {
    }

    /**
     * @return string[]
     */
    public function listen(): array
    {
        return [
            UserNameChanged::class,
            UserPasswordChanged::class,
            UserLoggedOut::class,
            UserLoggedIn::class,
        ];
    }

    /**
     * @param EventInterface $event
     */
    public function process(object $event): void
    {
        $payload = match ($event::class) {
            UserNameChanged::class => ['id' => $event->id->getValue(), 'name' => $event->name],
            UserPasswordChanged::class => ['id' => $event->id->getValue()],
            UserLoggedOut::class => ['id' => $event->id->getValue()],
            UserLoggedIn::class => ['id' => $event->id->getValue()],
        };

        /** @psalm-suppress NoInterfaceProperties */
        $this->publisher->publish('user_' . $event->id->getValue(), [
            'event' => $event->getName(),
            'payload' => $payload,
        ]);
    }
}
