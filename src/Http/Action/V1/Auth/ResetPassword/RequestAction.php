<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\ResetPassword;

use App\Auth\Command\ResetPassword\Request\Command;
use App\Auth\Command\ResetPassword\Request\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestAction implements RequestHandlerInterface
{
    public function __construct(private Handler $handler, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{email:?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->email = $data['email'] ?? '';

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(200);
    }
}
