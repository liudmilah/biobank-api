<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Command\CreateSample\Command;
use App\Biobank\Command\CreateSample\Handler;
use App\Http\Response\JsonResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CreateSampleAction implements RequestHandlerInterface
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

        $this->validator->validate($command);

        $sampleId = $this->handler->handle($command);

        return new JsonResponse(['id' => $sampleId]);
    }
}
