<?php

declare(strict_types=1);

namespace App\Biobank\Query\FindAllByType;

use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleTypeEnum;
use App\Biobank\Entity\Specie;
use Doctrine\DBAL\Connection;
use Jawira\CaseConverter\Convert;
use Webmozart\Assert\Assert;

final class Fetcher
{
    public function __construct(private Connection $connection)
    {
    }

    public function fetch(Query $query): ?array
    {
        Assert::notNull(SampleTypeEnum::tryFrom($query->type), 'Invalid sample type.');

        preg_match('/^([+-])([a-zA-Z]+)$/', $query->sort, $matches);

        Assert::notEmpty($sort = $matches[1] ?? null, 'Invalid sorting order.');

        $prop = $matches[2];

        Assert::false(!property_exists(Sample::class, $prop) && !property_exists(Specie::class, $prop), 'Invalid property name.');

        // sync with Sample::export
        $qb = $this->connection->createQueryBuilder();

        $orderBy = (new Convert($prop))->toSnake();

        $stmt = $qb
            ->select(['sm.*, sp.name_lat, sp.name_ru, sp.name_en'])
            ->from('samples', 'sm')
            ->leftJoin('sm', 'species', 'sp', 'sm.specie_id=sp.id')
            ->andWhere('sm.type=:type')
            ->setParameter('type', $query->type)
            ->setFirstResult($query->offset)
            ->setMaxResults($query->limit)
            ->addOrderBy($orderBy, $sort === '+' ? 'asc' : 'desc');

        if ($orderBy !== 'code') {
            $stmt->addOrderBy('code', 'asc');
        }

        if ($query->search) {
            $searchExpressions = array_map(
                static fn (string $s) => "lower({$s}) LIKE :search",
                ['code', 'name_lat', 'name_en', 'name_ru', 'place']
            );
            $stmt->andWhere($qb->expr()->or(...$searchExpressions));
            $stmt->setParameter('search', '%' . strtolower($query->search) . '%');
        }

        $result = [];
        foreach ($stmt->fetchAllAssociative() as $numRow => $row) {
            /** @var ?string $value */
            foreach ($row as $field => $value) {
                if (null !== $value) {
                    $result[$numRow][(new Convert($field))->toCamel()] = $value;
                }
            }
        }

        $smtp2 = clone $stmt;
        $amount = $smtp2
            ->setFirstResult(0)
            ->setMaxResults(null)
            ->executeQuery()
            ->rowCount();

        return [
            'list' => $result,
            'amount' => $amount,
        ];
    }
}
