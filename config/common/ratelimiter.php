<?php

declare(strict_types=1);

use App\Http\Middleware\RateLimiter;
use Psr\Container\ContainerInterface;
use Spatie\GuzzleRateLimiterMiddleware\InMemoryStore;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiter as Limiter;
use Spatie\GuzzleRateLimiterMiddleware\SleepDeferrer;

return [
    RateLimiter::class => static function (ContainerInterface $container): RateLimiter {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *    requests_per_second: int,
         * } $config
         */
        $config = $container->get('config')['rate_limiter'];

        return new RateLimiter(new Limiter(
            $config['requests_per_second'],
            Limiter::TIME_FRAME_SECOND,
            new InMemoryStore(),
            new SleepDeferrer()
        ));
    },

    'config' => [
        'rate_limiter' => [
            'requests_per_second' => 5,
        ],
    ],
];
