<?php

declare(strict_types=1);

namespace App\Event;

final class ListenerData
{
    public string $eventClass;

    /**
     * @var callable
     */
    public $listener;

    public function __construct(string $eventClass, callable $listener)
    {
        $this->eventClass = $eventClass;
        $this->listener = $listener;
    }
}
