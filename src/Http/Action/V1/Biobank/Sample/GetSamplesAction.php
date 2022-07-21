<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Query\FindAllByType\Fetcher;
use App\Biobank\Query\FindAllByType\Query;
use App\Http\Response\JsonResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetSamplesAction implements RequestHandlerInterface
{
    public function __construct(private Fetcher $fetcher, private Validator $validator)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        $query = new Query();
        $query->type = (string)($params['type'] ?? '');
        $query->sort = isset($params['sort']) ? $params['sort'] : '+code';
        $query->search = (string)($params['search'] ?? '');
        $query->limit = (int)($params['limit'] ?? 50);
        $query->offset = (int)($params['offset'] ?? 0);

        $this->validator->validate($query);

        $result = $this->fetcher->fetch($query);

        return new JsonResponse($result);
    }
}
