<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Salon;
use App\DTO\ResourceDTO;
use App\Entity\Resource;
use App\Entity\UserSalon;
use App\Entity\Appointment;
use Psr\Log\LoggerInterface;
use App\Model\ResourceTypeEnum;
use App\Entity\ResourceAvailability;
use App\DTO\Filters\ResourceFilterDTO;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\Filters\AppointmentFilterDTO;
use Symfony\Bundle\SecurityBundle\Security;

final class ResourceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private SalonService $salonService,
        private UserSalonService $userSalonService,
        private EntityHydratorService $hydrator,
        private Security $security,
        private LoggerInterface $logger,
        private ResourceAvailabilityService $resourceAvailabilityService,
        private ResourceExceptionService $resourceExceptionService,
        // private AppointmentService $appointmentService,
    ) {}

    public function searchResources(User $user, ResourceFilterDTO $filters): array
    {
        $userSalons = $user->getSalons()->map(fn(UserSalon $userSalon) => $userSalon->getSalon());
        $researchedSalons = $userSalons->filter(fn(Salon $salon) => count($filters->salons) === 0 || in_array($salon->getId(), $filters->salons));
        return $this->resourceRepository->findByFilters($filters, $researchedSalons);
    }

    public function getResource(int $id): ?Resource
    {
        $resource = $this->resourceRepository->find($id);

        if ($resource === null) {
            throw new \InvalidArgumentException('Recurso não encontrado');
        }

        $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());

        return $resource;
    }

    public function createResource(ResourceDTO $resourceDTO): Resource
    {
        $resource = $this->hydrator->hydrate(new Resource(), $resourceDTO);

        $salon = $this->salonService->getSalon($resourceDTO->salonId);
        $resource->setSalon($salon);

        $this->entityManager->persist($resource);
        $this->entityManager->flush();

        return $resource;
    }

    public function updateResource(ResourceDTO $resourceDTO, int $id): Resource
    {
        $resource = $this->getResource($id);

        $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());

        $this->hydrator->hydrate($resource, $resourceDTO);

        $this->entityManager->flush();

        return $resource;
    }

    public function deleteResource(int $id): void
    {
        $resource = $this->getResource($id);
        $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());

        $this->entityManager->remove($resource);
        $this->entityManager->flush();
    }

    public function getResourcesByType(User $user, string $type): array
    {
        $userSalons = $user->getSalons();

        $resources = [];
        foreach ($userSalons as $userSalon) {
            $resources = array_merge($resources, $userSalon->getSalon()->getResources()->filter(fn(Resource $resource) => $resource->getType() === $type)->toArray());
        }

        return array_map(fn(Resource $resource) => $resource->toArray(), $resources);
    }

    public function isAvailable(Resource $resource, \DateTimeImmutable $startsAt): bool
    {
        if (!$this->resourceAvailabilityService->isAvailable($resource, $startsAt)) {
            return false;
        }

        if ($this->resourceExceptionService->isUnavailable($resource, $startsAt)) {
            return false;
        }

        return true;
    }
}
