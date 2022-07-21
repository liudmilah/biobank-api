<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Service\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class UserRepository
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    /**
     * @param EntityRepository<User> $repo
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter('email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findBySignupConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['signupToken.value' => $token]);
    }

    public function findByPasswordResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['passwordResetToken.value' => $token]);
    }

    public function get(Id $id): User
    {
        $user = $this->repo->find($id->getValue());

        if ($user === null) {
            throw new DomainException('User is not found.');
        }

        return $user;
    }

    public function getByEmail(Email $email): User
    {
        $user = $this->repo->findOneBy(['email' => $email->getValue()]);

        if ($user === null) {
            throw new DomainException('User is not found.');
        }

        return $user;
    }

    public function getActiveByEmail(Email $email): User
    {
        $user = $this->repo->findOneBy(['email' => $email->getValue(), 'status' => Status::ACTIVE]);

        if ($user === null) {
            throw new DomainException('User is not found.');
        }

        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
