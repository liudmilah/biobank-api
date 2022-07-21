<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Specie;

use App\Biobank\Query\GetSpecies\Fetcher;
use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetSpecieAction implements RequestHandlerInterface
{
    public function __construct(private Fetcher $fetcher)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['species' => $this->fetcher->fetch()]);
    }
}
