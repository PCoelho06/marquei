<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected function paginate(QueryBuilder $queryBuilder, string $alias, int $page, int $limit, ?string $sort): array
    {
        $totalQueryBuilder = clone $queryBuilder;
        $total = (int) $totalQueryBuilder->select('COUNT(' . $alias . '.id)')->getQuery()->getSingleScalarResult();

        $totalPages = (int) ceil($total / $limit);
        $hasPrevious = $page > 1;
        $hasNext = $page < $totalPages;
        $first = ($page - 1) * $limit + 1;
        $last = $hasNext ? $page * $limit : $total;

        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($sort) {
            $sort = explode(',', $sort);
            $queryBuilder->orderBy($alias . '.' . $sort[0], $sort[1]);
        }

        $data = $queryBuilder->getQuery()->getResult();

        return [
            'data' => $data,
            'settings' => [
                'totalElements' => $total,
                'page' => $page,
                'limit' => $limit,
                'first' => $first,
                'last' => $last,
                'totalPages' => $totalPages,
                'hasPrevious' => $hasPrevious,
                'hasNext' => $hasNext
            ]
        ];
    }
}
