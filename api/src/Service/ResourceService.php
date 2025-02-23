<?php

namespace App\Service;

use App\DTO\ResourceDTO;
use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ResourceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ResourceRepository $resourceRepository,
        private SalonService $salonService,
        private EntityHydratorService $hydrator
    ) {}

    public function getResources(): array
    {
        $resources = $this->resourceRepository->findAll();
        return array_map(fn(Resource $resource) => $resource->toArray(), $resources);
    }

    public function getResource(int $id): ?Resource
    {
        $resource = $this->resourceRepository->find($id);

        if ($resource === null) {
            throw new \InvalidArgumentException('Recurso não encontrado');
        }

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
}
