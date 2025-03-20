<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected function paginate(QueryBuilder $queryBuilder, int $page, int $limit): array
    {
        $totalQueryBuilder = clone $queryBuilder;
        $total = (int) $totalQueryBuilder->select('COUNT(r.id)')->getQuery()->getSingleScalarResult();

        $totalPages = (int) ceil($total / $limit);
        $hasPrevious = $page > 1;
        $hasNext = $page < $totalPages;

        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $data = $queryBuilder->getQuery()->getResult();

        return [
            'data' => $data,
            'settings' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => $totalPages,
                'hasPrevious' => $hasPrevious,
                'hasNext' => $hasNext
            ]
        ];
    }
}
