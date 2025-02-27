<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Salon;
use App\Entity\UserSalon;
use App\Repository\UserSalonRepository;

class UserSalonService
{
    public function __construct(
        private UserSalonRepository $userSalonRepository,
    ) {}

    public function getUserSalon(User $user, Salon $salon): ?UserSalon
    {
        $userSalon = $this->userSalonRepository->findOneBy(['user' => $user, 'salon' => $salon]);

        if ($userSalon === null) {
            throw new \InvalidArgumentException('Usuário não pertence ao salão');
        }

        return $userSalon;
    }
}
