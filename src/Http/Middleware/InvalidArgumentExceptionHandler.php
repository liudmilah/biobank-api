<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\InvalidArgumentException;

final class InvalidArgumentExceptionHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse([
                'errors' => ['' => $exception->getMessage()],
            ], 422);
        }
    }
}
