<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Salon;
use App\DTO\Filters\SalonFilterDTO;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Salon>
 */
class SalonRepository extends AbstractRepository
{
    private const ALIAS = 's';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salon::class);
    }

    public function findSalon(int $id): ?Salon
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.businessHoursRanges', 'b')
            ->where(self::ALIAS . '.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByFilters(User $user, SalonFilterDTO $filters)
    {
        $qb = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.users', 'u')
            ->where('u.user = :user')
            ->setParameter('user', $user);

        if ($filters->name !== null) {
            $qb->andWhere($qb->expr()->like(self::ALIAS . '.name', ':name'))
                ->setParameter('name', '%' . $filters->name . '%');
        }

        return $this->paginate($qb, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }

    public function countSalons()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select('COUNT(' . self::ALIAS . '.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
