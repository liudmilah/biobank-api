<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Query\GetStatistics\Fetcher;
use App\Biobank\Query\GetStatistics\Query;
use App\Http\Response\JsonResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetStatisticsAction implements RequestHandlerInterface
{
    public function __construct(private Fetcher $fetcher, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $query = new Query();
        $query->type = (string)($params['type'] ?? '');

        $this->validator->validate($query);

        $result = $this->fetcher->fetch($query);

        return new JsonResponse(['statistics' => $result]);
    }
}
