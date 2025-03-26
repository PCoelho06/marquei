<?php

namespace App\Service;

use App\DTO\Filters\SalonFilterDTO;
use App\Entity\User;
use App\DTO\SalonDTO;
use App\Entity\Salon;
use App\Entity\Service;
use App\Entity\Resource;
use App\Model\RolesEnum;
use App\Entity\UserSalon;
use App\Repository\UserRepository;
use App\Entity\BusinessHoursRanges;
use App\Entity\Subscription;
use App\Repository\SalonRepository;
use App\Repository\ServiceRepository;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SalonService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SalonRepository $salonRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly ResourceRepository $resourceRepository,
        private UserSalonService $userSalonService,
        private UserRepository $userRepository,
        private EntityHydratorService $hydrator,
        private Security $security,
    ) {}

    public function createSalon(SalonDTO $salonDTO): Salon
    {
        $salon = $this->hydrator->hydrate(new Salon(), $salonDTO);
        $this->entityManager->persist($salon);

        $userSalon = $this->userSalonService->createUserSalon($this->security->getUser(), $salon, RolesEnum::ROLE_OWNER);
        $this->entityManager->persist($userSalon);

        $salon->addUser($userSalon);

        $this->entityManager->flush();

        return $salon;
    }

    public function getSalon(int $id): Salon
    {
        $salon = $this->salonRepository->find($id);

        if ($salon === null) {
            throw new \InvalidArgumentException('Salão não encontrado');
        }

        $this->userSalonService->checkUserIsSalonOwner($salon);

        return $salon;
    }

    public function searchSalons(SalonFilterDTO $filters): array
    {
        return $this->salonRepository->findByFilters($this->security->getUser(), $filters);
    }

    public function getSalonBusinessHours(int $id): array
    {
        $salon = $this->getSalon($id);

        $businessHours = $salon->getBusinessHoursRanges()->toArray();

        return array_map(fn(BusinessHoursRanges $businessHoursRanges) => $businessHoursRanges->toArray(), $businessHours);
    }

    public function getSalonServices(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $services = $this->serviceRepository->findByPaginated($salon, $page, $limit);

        return array_map(fn(Service $service) => $service->toArray(), $services);
    }

    public function getSalonEmployees(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $employees = $this->resourceRepository->findEmployeeBySalonPaginated($salon, $page, $limit);

        return array_map(fn(Resource $employee) => $employee->toArray(), $employees);
    }

    public function getSalonMachines(int $id, int $page, int $limit): array
    {
        $salon = $this->getSalon($id);

        $machines = $this->resourceRepository->findMachineBySalonPaginated($salon, $page, $limit);

        return array_map(fn(Resource $machine) => $machine->toArray(), $machines);
    }

    public function getSalonCurrentSubscription(int $id): Subscription
    {
        $salon = $this->getSalon($id);

        $subscription = $salon->getSubscriptions()->filter(fn($subscription) => $subscription->getStatus() == 'active')->first();

        if ($subscription === null || !$subscription) {
            throw new \InvalidArgumentException('Salão não possui assinatura ativa');
        }

        return $subscription;
    }

    public function updateSalon(int $id, SalonDTO $salonDTO): Salon
    {
        $salon = $this->getSalon($id);

        $this->userSalonService->checkUserIsSalonOwner($salon);

        $salon = $this->hydrator->hydrate($salon, $salonDTO);

        $this->entityManager->flush();

        return $salon;
    }

    public function deleteSalon(int $id): void
    {
        $salon = $this->getSalon($id);

        $this->userSalonService->checkUserIsSalonOwner($salon);

        $this->entityManager->remove($salon);
        $this->entityManager->flush();
    }

    public function getData(): array
    {
        $totalSalons = $this->salonRepository->countSalons();

        return [
            'totalSalons' => $totalSalons,
        ];
    }
}
