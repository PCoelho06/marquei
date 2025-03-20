<?php

namespace App\Repository;

use App\Entity\Salon;
use App\Entity\Resource;
use App\DTO\ResourceFilterDTO;
use App\Model\ResourceTypeEnum;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Resource>
 */
class ResourceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function findEmployeeBySalonPaginated(Salon $salon, int $page, int $limit): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.salon = :salon')
            ->setParameter('salon', $salon)
            ->andWhere('r.type = :type')
            ->setParameter('type', ResourceTypeEnum::EMPLOYEE)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findMachineBySalonPaginated(Salon $salon, int $page, int $limit): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.salon = :salon')
            ->setParameter('salon', $salon)
            ->andWhere('r.type = :type')
            ->setParameter('type', ResourceTypeEnum::MACHINE)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(ResourceFilterDto $filters, Collection $salons): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
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

        return $this->paginate($queryBuilder, $filters->page, $filters->limit);
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
