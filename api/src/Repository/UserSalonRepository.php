<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Salon;
use App\Entity\UserSalon;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<UserSalon>
 */
class UserSalonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSalon::class);
    }

    public function findOneByUserAndSalon(User $user, Salon $salon): ?UserSalon
    {
        return $this->createQueryBuilder('us')
            ->where('us.user = :user')
            ->andWhere('us.salon = :salon')
            ->setParameter('user', $user)
            ->setParameter('salon', $salon)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
