<?php

declare(strict_types=1);

namespace App\Auth\Query\FindByEmail;

use App\Auth\Entity\User\Status;
use App\Auth\Query\Identity;
use Doctrine\DBAL\Connection;

final class Fetcher
{
    public function __construct(private Connection $connection)
    {}

    public function fetch(string $email, bool $onlyActive = false): ?Identity
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(['id', 'role', 'email', 'name'])
            ->from('users')
            ->andWhere('email = :email')
            ->setParameter('email', $email);

        if ($onlyActive) {
            $stmt->andWhere('status = :status')
                ->setParameter('status', Status::ACTIVE);
        }

        /** @var array{id: string, role: string, email: string, name: string}|false */
        $row = $stmt->executeQuery()->fetchAssociative();

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
