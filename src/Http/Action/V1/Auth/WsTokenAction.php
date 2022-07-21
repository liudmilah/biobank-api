<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Service\Publisher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class WsTokenAction implements RequestHandlerInterface
{
    public function __construct(private Publisher $centrifugo)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        $token = $identity ? $this->centrifugo->generateToken($identity->id) : null;

        return new JsonResponse(['token' => $token]);
    }
}
