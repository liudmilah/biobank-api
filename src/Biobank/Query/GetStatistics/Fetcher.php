<?php

declare(strict_types=1);

namespace App\Biobank\Query\GetStatistics;

use App\Biobank\Entity\SampleTypeEnum;
use Doctrine\DBAL\Connection;
use DomainException;

final class Fetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(Query $query): array
    {
        if (null === SampleTypeEnum::tryFrom($query->type)) {
            throw new DomainException('Invalid sample type.');
        }

        return $this->connection->createQueryBuilder()
            ->select(['sp.name_lat AS name', 'COUNT(sm.code) as amount'])
            ->from('samples', 'sm')
            ->leftJoin('sm', 'species', 'sp', 'sm.specie_id=sp.id')
            ->where('sm.type = :type')
            ->setParameter('type', $query->type)
            ->groupBy('sp.name_lat')
            ->orderBy('COUNT(sm.code)', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
