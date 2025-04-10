<?php

namespace App\Repository;

use App\DTO\Filters\ClientFilterDTO;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends AbstractRepository<User>
 */
class UserRepository extends AbstractRepository implements PasswordUpgraderInterface
{
    private const ALIAS = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByFilters(ClientFilterDTO $filters)
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS);

        if ($filters->name !== null) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        $queryBuilder->expr()->concat(self::ALIAS . '.firstName', $queryBuilder->expr()->literal(' '), self::ALIAS . '.lastName'),
                        ':name'
                    ),
                    $queryBuilder->expr()->like(self::ALIAS . '.firstName', ':name'),
                    $queryBuilder->expr()->like(self::ALIAS . '.lastName', ':name')
                )
            )->setParameter('name', '%' . $filters->name . '%');
        }
        if ($filters->email !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like(self::ALIAS . '.email', ':email'))
                ->setParameter('email', '%' . $filters->email . '%');
        }
        if ($filters->phone !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like(self::ALIAS . '.phone', ':phone'))
                ->setParameter('phone', '%' . $filters->phone . '%');
        }
        if ($filters->salonId !== null) {
            $queryBuilder->innerJoin(self::ALIAS . '.userSalons', 'us')
                ->andWhere('us.salon = :salonId')
                ->setParameter('salonId', $filters->salonId);
        }

        return $this->paginate($queryBuilder, self::ALIAS, $filters->page, $filters->limit, $filters->sort);
    }
}
