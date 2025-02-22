<?php

namespace App\Service;

use App\DTO\SalonDTO;
use App\Entity\Salon;
use App\Repository\UserRepository;
use App\Entity\BusinessHoursRanges;
use App\Repository\SalonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
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

    public function checkUserIsSalonOwner(Salon $salon): void
    {
        if ($salon->getOwner()->getEmail() !== $this->security->getUser()->getUserIdentifier()) {
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
        $salons = $this->salonRepository->findAllPaginated($page, $limit);

        return array_map(fn(Salon $salon) => $salon->toArray(), $salons);
    }

    public function getSalon(int $id): Salon
    {
        $salon = $this->salonRepository->find($id);

        if ($salon === null) {
            throw new \InvalidArgumentException('Salão não encontrado');
        }

        return $salon;
    }

    public function getSalonBusinessHours(int $id): array
    {
        $salon = $this->getSalon($id);

        $businessHours = $salon->getBusinessHoursRanges()->toArray();

        return array_map(fn(BusinessHoursRanges $businessHoursRanges) => $businessHoursRanges->toArray(), $businessHours);
    }

    public function updateSalon(int $id, SalonDTO $salonDTO): Salon
    {
        $salon = $this->getSalon($id);

        $this->checkUserIsSalonOwner($salon);

        $salon = $this->hydrator->hydrate($salon, $salonDTO);

        $this->entityManager->flush();

        return $salon;
    }

    public function deleteSalon(int $id): void
    {
        $salon = $this->getSalon($id);

        $this->checkUserIsSalonOwner($salon);

        $this->entityManager->remove($salon);
        $this->entityManager->flush();
    }
}
