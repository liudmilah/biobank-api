<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class SampleRepository
{
    /**
     * @var EntityRepository<Sample>
     */
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    /**
     * @param EntityRepository<Sample> $repo
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function get(string $id, string $type = null): Sample
    {
        $criteria = array_merge(['id' => $id], $type ? ['type' => $type] : []);
        $sample = $this->repo->findOneBy($criteria);
        if ($sample === null) {
            throw new DomainException('Sample is not found.');
        }
        return $sample;
    }

    public function add(Sample $sample): void
    {
        $this->em->persist($sample);
    }

    public function remove(Sample $sample): void
    {
        $this->em->remove($sample);
    }

    public function deleteByIds(array $ids, string $type): void
    {
        $this->repo->createQueryBuilder('t')
            ->delete(Sample::class, 's')
            ->andWhere('s.id IN (:ids)')
            ->andWhere('s.type = :type')
            ->setParameter('ids', $ids)
            ->setParameter('type', $type)
            ->getQuery()
            ->execute();
    }

    public function deleteAll(string $type): void
    {
        $this->repo->createQueryBuilder('t')
            ->delete(Sample::class, 's')
            ->where('s.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->execute();
    }

    /**
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hasByCode(string $type, Code $code): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.code)')
            ->andWhere('t.code = :code')
            ->andWhere('t.type = :type')
            ->setParameter('code', $code->getValue())
            ->setParameter('type', $type)
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param Code[] $codes
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return string[]
     */
    public function getExistingCodes(string $type, array $codes): array
    {
        /** @var string[] $data */
        $data = $this->repo->createQueryBuilder('t')
            ->select('t.code')
            ->andWhere('t.code IN (:code)')
            ->andWhere('t.type = :type')
            ->setParameter('code', array_map(static fn (Code $c) => $c->getValue(), $codes))
            ->setParameter('type', $type)
            ->getQuery()->getSingleColumnResult();

        return $data;
    }
}
