<?php

declare(strict_types=1);

use App\Event\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

return [
    ListenerProviderInterface::class => static function (ContainerInterface $container): ListenerProvider {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array {listeners: string[]} $config
         */
        $config = $container->get('config')['events'];
        /**
         * @psalm-suppress MixedArgument
         */
        return new ListenerProvider($config['listeners'], $container);
    },

    'config' => [
        'events' => [
            'listeners' => [
                \App\Event\Listeners\UserListener::class,
                \App\Event\Listeners\SampleListener::class,
            ],
        ],
    ],
];
