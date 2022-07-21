<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Auth\Query\FindById\Fetcher;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\EmptyResponse;
use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserAction implements RequestHandlerInterface
{
    public function __construct(private Fetcher $users)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        $user = $identity ? $this->users->fetch($identity->id) : null;

        if (!$user) {
            return new EmptyResponse(401);
        }

        return new JsonResponse([
            'id' => $user?->id,
            'role' => $user?->role,
            'name' => $user?->name,
            'email' => $user?->email,
        ]);
    }
}
