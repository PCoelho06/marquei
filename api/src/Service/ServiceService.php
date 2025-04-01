<?php

namespace App\Service;

use App\DTO\ServiceDTO;
use App\Entity\Service;
use App\Service\ResourceService;
use App\DTO\Filters\ServiceFilterDTO;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ServiceService
{
    public function __construct(
        private ServiceRepository $serviceRepository,
        private EntityManagerInterface $entityManager,
        private EntityHydratorService $hydrator,
        private SalonService $salonService,
        private ResourceService $resourceService,
        private UserSalonService $userSalonService,
    ) {}

    public function searchServices(ServiceFilterDTO $filters): array
    {
        $salon = $this->salonService->getSalon($filters->salonId);
        return $this->serviceRepository->findByFilters($filters, $salon);
    }

    public function getService(int $id): ?Service
    {
        $service = $this->serviceRepository->find($id);

        if ($service === null) {
            throw new \InvalidArgumentException('Prestação de serviço não encontrada');
        }

        return $service;
    }

    public function createService(ServiceDTO $serviceDTO): Service
    {
        $service = $this->hydrator->hydrate(new Service(), $serviceDTO);

        $salon = $this->salonService->getSalon($serviceDTO->salonId);
        $service->setSalon($salon);

        foreach ($serviceDTO->resourcesIds as $resourceId) {
            $resource = $this->resourceService->getResource($resourceId);
            if ($resource === null) {
                throw new \InvalidArgumentException('Recurso não encontrado');
            }
            $service->addResource($resource);
        }

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return $service;
    }

    public function updateService(ServiceDTO $serviceDTO, int $id): Service
    {
        $service = $this->getService($id);

        $this->userSalonService->checkUserIsSalonOwner($service->getSalon());

        $service = $this->hydrator->hydrate($service, $serviceDTO);

        foreach ($serviceDTO->resourcesIds as $resourceId) {
            $resource = $this->resourceService->getResource($resourceId);
            if ($resource === null) {
                throw new \InvalidArgumentException('Recurso não encontrado');
            }
            if ($service->getResources()->contains($resource)) {
                continue;
            }
            $service->addResource($resource);
        }

        $this->entityManager->flush();

        return $service;
    }

    public function deleteService(int $id): void
    {
        $service = $this->getService($id);
        $this->userSalonService->checkUserIsSalonOwner($service->getSalon());

        $this->entityManager->remove($service);
        $this->entityManager->flush();
    }
}
