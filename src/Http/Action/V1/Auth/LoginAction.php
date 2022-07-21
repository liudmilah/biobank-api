<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Auth\Command\Login\Command;
use App\Auth\Command\Login\Handler;
use App\Auth\Query\FindByEmail\Fetcher;
use App\Auth\Service\AuthTokenGenerator\AuthTokenGenerator;
use App\Auth\Service\AuthTokenGenerator\Params;
use App\Http\AuthCookieTrait;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class LoginAction implements RequestHandlerInterface
{
    use AuthCookieTrait;

    public function __construct(
        private Handler $handler,
        private Validator $validator,
        private Fetcher $users,
        private AuthTokenGenerator $authTokenGenerator
    )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{email:?string, password:?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();

        $command->email = $data['email'] ?? '';

        $command->password = $data['password'] ?? '';

        $this->validator->validate($command);

        $this->handler->handle($command);

        $user = $this->users->fetch($command->email);

        $tokenParams = new Params($user->id, $user->email, $user->role, $now = new \DateTimeImmutable());

        return $this->setAuthCookies(
            new EmptyResponse(200),
            $this->authTokenGenerator->generateAccessToken($tokenParams),
            $this->authTokenGenerator->generateRefreshToken($tokenParams),
            $now
        );
    }
}
