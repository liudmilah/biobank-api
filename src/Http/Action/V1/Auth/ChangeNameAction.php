<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth;

use App\Auth\Command\ChangeName\Command;
use App\Auth\Command\ChangeName\Handler;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ChangeNameAction implements RequestHandlerInterface
{
    public function __construct(private Handler $handler, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{name:?string} $data
         */
        $data = $request->getParsedBody();

        $identity = Authenticate::identity($request);

        $command = new Command();
        $command->name = $data['name'] ?? '';
        $command->id = $identity->id ?? '';

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(204);
    }
}
