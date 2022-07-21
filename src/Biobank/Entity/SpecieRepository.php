<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class SpecieRepository
{
    /**
     * @var EntityRepository<Specie>
     */
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    /**
     * @param EntityRepository<Specie> $repo
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function get(string $specieId): Specie
    {
        $specie = $this->repo->find($specieId);
        if ($specie === null) {
            throw new DomainException('Specie is not found.');
        }
        return $specie;
    }

    public function add(Specie $specie): void
    {
        $this->em->persist($specie);
    }

    public function remove(Specie $specie): void
    {
        $this->em->remove($specie);
    }

    public function hasByLatName(SpecieName $lat): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.nameLat = :lat')
            ->setParameter('lat', $lat->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param SpecieName[]  $namesLat
     * @throws \Doctrine\ORM\Query\QueryException
     * @return Specie[]
     */
    public function findAllByLatNames(array $namesLat): array
    {
        /** @var Specie[] $data */
        $data = $this->repo->createQueryBuilder('t')
            ->where('t.nameLat IN (:lat)')
            ->setParameter('lat', array_map(static fn (SpecieName $name) => $name->getValue(), $namesLat))
            ->indexBy('t', 't.nameLat')
            ->getQuery()
            ->execute();

        return $data;
    }
}
