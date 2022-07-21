<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Auth\Events\UserLoggedOut;
use App\Event\EventDispatcher;
use App\Http\AuthCookieTrait;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\EmptyResponse;
use App\Service\Id;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class LogoutAction implements RequestHandlerInterface
{
    use AuthCookieTrait;

    public function __construct(private EventDispatcher $eventDispatcher)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        $userId = $identity->id ?? '';

        $response = new EmptyResponse(200);

        if ($userId) {
            $this->eventDispatcher->dispatch(new UserLoggedOut(new Id($userId)));

            $response = $this->resetAuthCookies($response);
        }

        return $response;
    }
}
