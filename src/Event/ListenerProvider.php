<?php

declare(strict_types=1);

namespace App\Event;

use Psr\Container\ContainerInterface;

final class ListenerProvider implements \Psr\EventDispatcher\ListenerProviderInterface
{
    /**
     * @var ListenerData[]
     */
    public array $listeners = [];

    /**
     * @param string[] $listenersClasses
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(array $listenersClasses, ContainerInterface $container)
    {
        foreach ($listenersClasses as $listenerClass) {
            /** @var ListenerInterface $listener */
            $listener = $container->get($listenerClass);
            if ($listener instanceof ListenerInterface) {
                foreach ($listener->listen() as $eventClass) {
                    $this->listeners[] = new ListenerData($eventClass, [$listener, 'process']);
                }
            }
        }
    }

    /**
     * @return callable[]
     */
    public function getListenersForEvent(object $event): iterable
    {
        $result = [];
        foreach ($this->listeners as $listener) {
            if ($event instanceof $listener->eventClass) {
                $result[] = $listener->listener;
            }
        }
        return $result;
    }
}
