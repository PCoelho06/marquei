<?php

namespace App\Repository;

use App\Entity\Salon;
use App\Entity\Resource;
use App\DTO\Filters\ResourceFilterDTO;
use App\Model\ResourceTypeEnum;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Resource>
 */
class ResourceRepository extends AbstractRepository
{
    private const ALIAS = 'r';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function findByFilters(ResourceFilterDto $filters, Collection $salons): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)
            ->where('r.salon IN (:salons)')
            ->setParameter('salons', $salons);

        if (!empty($filters->types)) {
            $queryBuilder->andWhere('r.type IN (:types)')
                ->setParameter('types', $filters->types);
        }

        if (!empty($filters->name)) {
            $queryBuilder->andWhere('r.name LIKE :name')
                ->setParameter('name', "%{$filters->name}%");
        }

        return $this->paginate($queryBuilder, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }


    //    /**
    //     * @return Resource[] Returns an array of Resource objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Resource
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
