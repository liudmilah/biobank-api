<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

use App\Auth\Service\AuthTokenGenerator\AccessToken;
use App\Auth\Service\AuthTokenGenerator\AuthTokenGenerator;
use App\Auth\Service\AuthTokenGenerator\Params;
use App\Auth\Service\AuthTokenGenerator\RefreshToken;
use App\Http\AuthCookieTrait;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

final class Authenticate implements MiddlewareInterface
{
    use AuthCookieTrait;

    private const ATTRIBUTE = 'identity';

    public function __construct(private AuthTokenGenerator $authTokenGenerator, private array $routes) {}

    public static function identity(ServerRequestInterface $request): ?Identity
    {
        $identity = $request->getAttribute(self::ATTRIBUTE);

        if ($identity !== null && !$identity instanceof Identity) {
            throw new LogicException('Invalid identity.');
        }

        return $identity;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->shouldAuthenticate($request)) {
            return $handler->handle($request);
        }

        $now = new \DateTimeImmutable();

        try {
            $tokens = $this->fetchTokens($request, $now);
        } catch (\Throwable) {
            return (new ResponseFactory())->createResponse(401);
        }

        $identity = new Identity(
            id: $tokens['accessToken']->getUserId(),
            role: $tokens['accessToken']->getUserRole(),
            email: $tokens['accessToken']->getUserEmail(),
        );

        $request = $request->withAttribute(self::ATTRIBUTE, $identity);

        $response = $handler->handle($request);

        if ($tokens['refreshed']) {
            $response = $this->setAuthCookies($response, $tokens['accessToken'], $tokens['refreshToken'], $now);
        }

        return $response;
    }

    private function shouldAuthenticate(ServerRequestInterface $request): bool
    {
        $uri = "/" . $request->getUri()->getPath();
        $uri = (string) preg_replace("@/+@", "/", $uri);
        foreach ($this->routes as $path) {
            $path = rtrim($path, "/");
            if (!!preg_match("@^{$path}(/.*)?$@", $uri)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @param $now
     * @return array{accessToken: ?AccessToken, refreshToken: ?RefreshToken, refreshed: bool}
     */
    private function fetchTokens(ServerRequestInterface $request, $now): array
    {
        $refreshToken = null;
        $refreshed = false;
        $accessTokenJwt = $this->getAccessTokenJwt($request);
        $refreshTokenJwt = $this->getRefreshTokenJwt($request);

        if (!$accessTokenJwt && !$refreshTokenJwt) {
            throw new \DomainException('Empty tokens.');
        }

        if (!$accessTokenJwt) {
            $refreshToken = $this->authTokenGenerator->getRefreshTokenFromJwt($refreshTokenJwt, $now);

            $params = new Params(
                $refreshToken->getUserId(),
                $refreshToken->getUserEmail(),
                $refreshToken->getUserRole(),
                $now
            );

            $accessToken = $this->authTokenGenerator->generateAccessToken($params);

            $refreshToken = $this->authTokenGenerator->generateRefreshToken($params);

            $refreshed = true;
        } else {
            $accessToken = $this->authTokenGenerator->getAccessTokenFromJwt($accessTokenJwt, $now);
        }

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'refreshed' => $refreshed,
        ];
    }
}
