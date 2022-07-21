<?php

declare(strict_types=1);

namespace App\Biobank\Query\GetSpecies;

use Doctrine\DBAL\Connection;
use Jawira\CaseConverter\Convert;

final class Fetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(): ?array
    {
        $data = $this->connection->createQueryBuilder()
            ->select(['id, name_lat, name_ru, name_en'])
            ->from('species')
            ->addOrderBy('name_lat', 'asc')
            ->executeQuery()
            ->fetchAllAssociative();

        $result = [];
        foreach ($data as $numRow => $row) {
            /** @var ?string $value */
            foreach ($row as $field => $value) {
                if (null !== $value) {
                    $result[$numRow][(new Convert($field))->toCamel()] = $value;
                }
            }
        }

        return $result;
    }
}
