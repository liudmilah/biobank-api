<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Specie;

use App\Biobank\Command\DeleteSpecie\Command;
use App\Biobank\Command\DeleteSpecie\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DeleteSpecieAction implements RequestHandlerInterface
{
    public function __construct(private Handler $handler, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command();

        $command->id = (string)$request->getAttribute('id');

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(204);
    }
}
