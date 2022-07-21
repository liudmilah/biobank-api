<?php

declare(strict_types=1);

namespace App\Event;

interface ListenerInterface
{
    /**
     * @return string[] events list
     */
    public function listen(): array;

    /**
     * handle an event.
     * @param EventInterface $event
     */
    public function process(object $event): void;
}
