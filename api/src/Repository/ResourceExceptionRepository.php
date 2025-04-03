<?php

namespace App\Repository;

use App\Entity\Resource;
use App\Entity\ResourceException;
use App\DTO\Filters\BaseFilterDTO;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ResourceException>
 */
class ResourceExceptionRepository extends AbstractRepository
{
    private const ALIAS = 're';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResourceException::class);
    }

    public function findByResourceFiltered(Resource $resource, BaseFilterDTO $filters): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS . '.resource = :resource')
            ->setParameter('resource', $resource);

        return $this->paginate($queryBuilder, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }

    //    /**
    //     * @return ResourceException[] Returns an array of ResourceException objects
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

    //    public function findOneBySomeField($value): ?ResourceException
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
