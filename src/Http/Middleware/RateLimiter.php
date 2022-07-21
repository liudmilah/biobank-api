<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiter as Limiter;

final class RateLimiter implements \Psr\Http\Server\MiddlewareInterface
{
    public function __construct(private Limiter $rateLimiter)
    {
    }

    /**
     * @psalm-suppress MixedInferredReturnType, MixedReturnStatement
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->rateLimiter->handle(static fn (): ResponseInterface => $handler->handle($request));
    }
}
