<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Command\UpdateSample\Command;
use App\Biobank\Command\UpdateSample\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UpdateSampleAction implements RequestHandlerInterface
{
    public function __construct(private Validator $validator, private Handler $handler)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command();

        $data = $request->getParsedBody();

        foreach (array_keys(get_class_vars($command::class)) as $k) {
            if (isset($data[$k])) {
                $command->{$k} = $data[$k];
            }
        }

        $command->id = (string)$request->getAttribute('id');

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}
