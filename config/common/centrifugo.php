<?php

declare(strict_types=1);

use App\Service\Publisher;
use phpcent\Client;
use Psr\Container\ContainerInterface;
use function App\env;

return [
    Publisher::class => static function (ContainerInterface $container): Publisher {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *    url: string,
         *    api_key: string,
         *    secret: string,
         * } $config
         */
        $config = $container->get('config')['centrifugo'];
        $client = new Client($config['url'], $config['api_key'], $config['secret']);

        return new Publisher($client);
    },

    'config' => [
        'centrifugo' => [
            'url' => env('CENTRIFUGO_URL'),
            'secret' => env('CENTRIFUGO_SECRET'),
            'api_key' => env('CENTRIFUGO_API_KEY'),
        ],
    ],
];
