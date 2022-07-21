<?php

declare(strict_types=1);

use Predis\Client;
use Psr\Container\ContainerInterface;
use function App\env;

return [
    Client::class => static function (ContainerInterface $container): Client {
        /**
         * @psalm-suppress MixedAssignment
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     server: array,
         *     client: array
         * } $config
         */
        $config = (array)$container->get('config')['cache'];
        /** @psalm-suppress MixedArrayAccess */
        return new Client($config['server'], $config['client']);
    },

    'config' => [
        'cache' => [
            'server' => [
                'scheme' => 'tcp',
                'host' => 'bb-cache',
                'port' => '6379',
            ],
            'client' => [
                'parameters' => [
                    'password' => env('CACHE_PASSWORD'),
                ],
            ],
        ],
    ],
];
