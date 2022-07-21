<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Command\DeleteAll\Command;
use App\Biobank\Command\DeleteAll\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DeleteAllAction implements RequestHandlerInterface
{
    public function __construct(private Handler $handler, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command();

        $params = $request->getQueryParams();
        $command->type = (string)($params['type'] ?? '');

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(204);
    }
}
