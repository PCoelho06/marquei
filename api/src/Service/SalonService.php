<?php

namespace App\Service;

use App\DTO\SalonDTO;
use App\Entity\Salon;
use App\Repository\UserRepository;
use App\Repository\SalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SalonService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SalonRepository $salonRepository,
        private UserRepository $userRepository,
        private EntityHydratorService $hydrator,
        private Security $security,
    ) {}

    public function checkUserIsSalonOwner(int $salonId): void
    {
        $salon = $this->getSalon($salonId);

        if ($salon->getOwner()->getId() !== $this->security->getUser()->getId()) {
            throw new AccessDeniedException('Usuário não é o proprietário do salão');
        }
    }

    public function createSalon(SalonDTO $salonDTO): Salon
    {
        $salon = $this->hydrator->hydrate(new Salon(), $salonDTO);

        $salon->setOwner($this->security->getUser());

        $this->entityManager->persist($salon);
        $this->entityManager->flush();

        return $salon;
    }

    public function listSalons(int $page = 1, int $limit = 10): array
    {
        return $this->salonRepository->findAllPaginated($page, $limit);
    }

    public function getSalon(int $id): Salon
    {
        $salon = $this->salonRepository->find($id);

        if ($salon === null) {
            throw new \InvalidArgumentException('Salão não encontrado');
        }

        return $salon;
    }

    public function updateSalon(int $id, SalonDTO $salonDTO): Salon
    {
        $salon = $this->getSalon($id);

        $salon = $this->hydrator->hydrate($salon, $salonDTO);

        $this->entityManager->flush();

        return $salon;
    }

    public function deleteSalon(int $id): void
    {
        $salon = $this->getSalon($id);

        $this->entityManager->remove($salon);
        $this->entityManager->flush();
    }
}
