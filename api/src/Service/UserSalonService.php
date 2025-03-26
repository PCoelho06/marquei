<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Salon;
use App\Model\RolesEnum;
use App\Entity\UserSalon;
use App\Repository\UserSalonRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserSalonService
{
    public function __construct(
        private UserSalonRepository $userSalonRepository,
        private Security $security,
    ) {}

    public function checkUserIsSalonOwner(Salon $salon): void
    {
        $user = $this->security->getUser();

        if ($user === null) {
            throw new AccessDeniedException('Usuário não autenticado');
        }

        $userSalon = $this->getUserSalon($user, $salon);

        if ($userSalon === null || $userSalon->getRole() !== RolesEnum::ROLE_OWNER) {
            throw new AccessDeniedException('Usuário não é o proprietário do salão');
        }
    }

    public function hasSalons(User $user): bool
    {
        return $this->userSalonRepository->findOneBy(['user' => $user]) !== null;
    }

    public function getUserSalon(User $user, Salon $salon): ?UserSalon
    {
        $userSalon = $this->userSalonRepository->findOneBy(['user' => $user, 'salon' => $salon]);

        if ($userSalon === null) {
            throw new \InvalidArgumentException('Usuário não pertence ao salão');
        }

        return $userSalon;
    }

    public function createUserSalon(User $user, Salon $salon, RolesEnum $role): UserSalon
    {
        $userSalon = new UserSalon();
        $userSalon->setUser($user);
        $userSalon->setSalon($salon);
        $userSalon->setRole($role);

        return $userSalon;
    }
}
