<?php

namespace App\Service;

use App\Entity\User;
use App\DTO\ResourceDTO;
use App\Entity\Resource;
use App\Entity\UserSalon;
use App\DTO\ResourceFilterDTO;
use App\Model\ResourceTypeEnum;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class ResourceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private SalonService $salonService,
        private EntityHydratorService $hydrator,
        private Security $security,
    ) {}

    public function searchResources(User $user, ResourceFilterDTO $filters): array
    {
        $userSalons = $user->getSalons()->map(fn(UserSalon $userSalon) => $userSalon->getSalon());
        return $this->resourceRepository->findByFilters($filters, $userSalons);
    }

    public function getResource(int $id): ?Resource
    {
        $resource = $this->resourceRepository->find($id);

        if ($resource === null) {
            throw new \InvalidArgumentException('Recurso não encontrado');
        }

        $this->salonService->checkUserIsSalonOwner($resource->getSalon());

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

        $this->salonService->checkUserIsSalonOwner($resource->getSalon());

        $this->hydrator->hydrate($resource, $resourceDTO);

        $this->entityManager->flush();

        return $resource;
    }

    public function deleteResource(int $id): void
    {
        $resource = $this->getResource($id);
        $this->salonService->checkUserIsSalonOwner($resource->getSalon());

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
}
