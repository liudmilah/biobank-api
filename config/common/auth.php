<?php

declare(strict_types=1);

use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\AuthTokenGenerator\AuthTokenGenerator;
use App\Auth\Service\JwtTokenizer;
use App\Auth\Service\Tokenizer;
use App\Http\Middleware\Auth\Authenticate;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use function App\env;

return [
    UserRepository::class => static function (ContainerInterface $container): UserRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(User::class);
        return new UserRepository($em, $repo);
    },

    Tokenizer::class => static function (ContainerInterface $container): Tokenizer {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{token_ttl:string} $config
         */
        $config = $container->get('config')['auth'];

        return new Tokenizer(new DateInterval($config['token_ttl']));
    },

    JwtTokenizer::class => static function (ContainerInterface $container): JwtTokenizer {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     algorithm: string,
         *     secret: string
         * } $config
         */
        $config = $container->get('config')['auth']['jwt_token'];

        return new JwtTokenizer($config['secret'], $config['algorithm']);
    },

    AuthTokenGenerator::class => static function (ContainerInterface $container): AuthTokenGenerator {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     access_token_ttl: string,
         *     refresh_token_ttl: string,
         * } $config
         */
        $config = $container->get('config')['auth'];

        return new AuthTokenGenerator(
            $container->get(JwtTokenizer::class),
            new \DateInterval($config['access_token_ttl']),
            new \DateInterval($config['refresh_token_ttl']),
        );
    },

    Authenticate::class => static function (ContainerInterface $container): Authenticate {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     auth_routes: array<string>,
         * } $config
         */
        $config = $container->get('config')['auth'];

        return new Authenticate(
            $container->get(AuthTokenGenerator::class),
            $config['auth_routes']
        );
    },

    'config' => [
        'auth' => [
            'token_ttl' => 'PT1H',
            'auth_routes' => [
                '/v1/bank',
                '/v1/auth/user',
                '/v1/auth/logout',
                '/v1/auth/ws-token',
            ],
            'access_token_ttl' => 'PT30M',
            'refresh_token_ttl' => 'PT24H',
            'jwt_token' => [
                'secret' => env('JWT_SECRET'),
                'algorithm' => 'HS256',
            ],
        ],
    ],
];
