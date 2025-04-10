<?php

namespace App\Service;

use App\Entity\User;
use App\DTO\ClientDTO;
use App\Repository\UserRepository;
use App\DTO\Filters\ClientFilterDTO;
use App\Entity\UserSalon;
use App\Model\RolesEnum;
use Doctrine\ORM\EntityManagerInterface;

class ClientService
{
    public function __construct(
        private FilterService $filterService,
        private UserRepository $userRepository,
        private EntityHydratorService $hydrator,
        private EntityManagerInterface $entityManager,
        private SalonService $salonService,
    ) {}

    public function searchClients(ClientFilterDTO $filters): array
    {
        return $this->userRepository->findByFilters($filters);
    }

    public function createClient(ClientDTO $clientDTO): void
    {
        $client = $this->hydrator->hydrate(new User(), $clientDTO);
        $this->entityManager->persist($client);

        $salon = $this->salonService->getSalon($clientDTO->salonId);

        $userSalon = new UserSalon();
        $userSalon->setUser($client);
        $userSalon->setSalon($salon);
        $userSalon->setRole(RolesEnum::ROLE_CLIENT);
        $this->entityManager->persist($userSalon);

        $this->entityManager->flush();
    }

    public function getClient(int $id): User
    {
        $client = $this->userRepository->find($id);

        if ($client === null) {
            throw new \InvalidArgumentException('Cliente não encontrado');
        }

        return $client;
    }
}
