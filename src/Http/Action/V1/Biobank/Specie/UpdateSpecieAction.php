<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Specie;

use App\Biobank\Command\UpdateSpecie\Command;
use App\Biobank\Command\UpdateSpecie\Handler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UpdateSpecieAction implements RequestHandlerInterface
{
    public function __construct(private Handler $handler, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command();

        /**
         * @var array{
         *     nameLat:?string,
         *     nameEn:?string,
         *     nameRu:?string,
         *     family:?string,
         *     order:?string
         * } $data
         */
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
