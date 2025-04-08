<?php

namespace App\Repository;

use App\DTO\Filters\AppointmentFilterDTO;
use App\Entity\Appointment;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentRepository extends AbstractRepository
{
    private const ALIAS = 'a';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findByFilters(AppointmentFilterDTO $filters)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.salon', 'sa')
            ->leftJoin(self::ALIAS . '.client', 'c')
            ->leftJoin(self::ALIAS . '.service', 'se')
            ->leftJoin(self::ALIAS . '.resource', 'r');

        if ($filters->salonId) {
            $queryBuilder->andWhere('sa.id = :salonId')
                ->setParameter('salonId', $filters->salonId);
        }

        if ($filters->clientId) {
            $queryBuilder->andWhere('c.id = :clientId')
                ->setParameter('clientId', $filters->clientId);
        }
        if ($filters->serviceId) {
            $queryBuilder->andWhere('se.id = :serviceId')
                ->setParameter('serviceId', $filters->serviceId);
        }
        if ($filters->resourceId) {
            $queryBuilder->andWhere('r.id = :resourceId')
                ->setParameter('resourceId', $filters->resourceId);
        }
        if ($filters->startsAt) {
            $queryBuilder->andWhere('a.startsAt >= :startsAt')
                ->setParameter('startsAt', \DateTimeImmutable::createFromFormat('d/m/Y, H:i', $filters->startsAt));
        }
        if ($filters->endsAt) {
            $queryBuilder->andWhere('a.endsAt <= :endsAt')
                ->setParameter('endsAt', \DateTimeImmutable::createFromFormat('d/m/Y, H:i', $filters->endsAt));
        }
        if ($filters->status) {
            $queryBuilder->andWhere('a.status = :status')
                ->setParameter('status', $filters->status);
        }

        return $this->paginate($queryBuilder, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }
}
