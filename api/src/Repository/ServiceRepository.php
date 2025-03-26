<?php

namespace App\Repository;

use App\DTO\Filters\ServiceFilterDTO;
use App\Entity\Salon;
use App\Entity\Service;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends AbstractRepository
{
    private const ALIAS = 's';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findByPaginated(Salon $salon, int $page, int $limit): array
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->where('s.salon = :salon')
            ->setParameter('salon', $salon)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(ServiceFilterDTO $filters, Salon $salon): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)
            ->where('s.salon = :salon')
            ->setParameter('salon', $salon);

        if (!empty($filters->name)) {
            $queryBuilder->andWhere('s.name LIKE :name')
                ->setParameter('name', '%' . $filters->name . '%');
        }

        if (!empty($filters->minPrice) && $filters->minPrice > 0 && $filters->minPrice <= $filters->maxPrice) {
            $queryBuilder->andWhere('s.price >= :minPrice')
                ->setParameter('minPrice', $filters->minPrice);
        }

        if (!empty($filters->maxPrice) && $filters->maxPrice > 0 && $filters->maxPrice >= $filters->minPrice) {
            $queryBuilder->andWhere('s.price <= :maxPrice')
                ->setParameter('maxPrice', $filters->maxPrice);
        }

        if (!empty($filters->minDuration) && $filters->minDuration > 0 && $filters->minDuration <= $filters->maxDuration) {
            $queryBuilder->andWhere('s.duration >= :minDuration')
                ->setParameter('minDuration', $filters->minDuration);
        }

        if (!empty($filters->maxDuration) && $filters->maxDuration > 0 && $filters->maxDuration >= $filters->minDuration) {
            $queryBuilder->andWhere('s.duration <= :maxDuration')
                ->setParameter('maxDuration', $filters->maxDuration);
        }

        return $this->paginate($queryBuilder, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
