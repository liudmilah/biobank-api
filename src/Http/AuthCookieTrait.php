<?php
declare(strict_types=1);

namespace App\Http;

use App\Auth\Service\AuthTokenGenerator\{AccessToken, RefreshToken};
use Dflydev\FigCookies\{ FigResponseCookies, SetCookie, Modifier\SameSite };
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait AuthCookieTrait
{
    private string $accessTokenCookieName = 'access_token';
    private string $refreshTokenCookieName = 'refresh_token';

    public function setAuthCookies(
        ResponseInterface $response,
        AccessToken $accessToken,
        RefreshToken $refreshToken,
        \DateTimeImmutable $now
    ): ResponseInterface
    {
        $response = $this->responseWithCookie(
            $response,
            $this->accessTokenCookieName,
            $accessToken->toJWT(),
            $accessToken->getExpiredAt() - $now->getTimestamp()
        );

        $response = $this->responseWithCookie(
            $response,
            $this->refreshTokenCookieName,
            $refreshToken->toJWT(),
            $refreshToken->getExpiredAt() - $now->getTimestamp()
        );

        return $response;
    }

    public function resetAuthCookies(ResponseInterface $response): ResponseInterface
    {
        $response = $this->responseWithCookie($response, $this->accessTokenCookieName, '', 0);

        $response = $this->responseWithCookie($response, $this->refreshTokenCookieName, '', 0);

        return $response;
    }

    public function getAccessTokenJwt(ServerRequestInterface $request): ?string
    {
        $cookieParams = $request->getCookieParams();

        return $cookieParams[$this->accessTokenCookieName] ?? null;
    }

    public function getRefreshTokenJwt(ServerRequestInterface $request): ?string
    {
        $cookieParams = $request->getCookieParams();

        return $cookieParams[$this->refreshTokenCookieName] ?? null;
    }

    private function responseWithCookie(ResponseInterface $response, string $name, string $value, int $maxAge) {
        return FigResponseCookies::set(
            $response,
            SetCookie::create($name)
                ->withPath('/')
                ->withValue($value)
                ->withMaxAge($maxAge)
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite(SameSite::strict())
        );
    }
}