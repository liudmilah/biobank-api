<?php

declare(strict_types=1);

namespace App\Auth\Query\FindById;

use App\Auth\Query\Identity;
use Doctrine\DBAL\Connection;

final class Fetcher
{

    public function __construct(private Connection $connection)
    {}

    public function fetch(string $id): ?Identity
    {
        /** @var array{id: string, role: string, email: string, name: string}|false */
        $row = $this->connection->createQueryBuilder()
            ->select(['id', 'role', 'email', 'name'])
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Identity(
            $row['id'],
            $row['role'],
            $row['email'],
            $row['name'],
        );
    }
}
