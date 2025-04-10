<?php

namespace App\Service;

use App\DTO\Filters\BaseFilterDTO;
use App\Entity\Resource;
use App\Service\ResourceService;
use App\DTO\ResourceExceptionDTO;
use App\Entity\ResourceException;
use App\Repository\ResourceExceptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResourceExceptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityHydratorService $hydrator,
        private ResourceExceptionRepository $resourceExceptionRepository
    ) {}

    public function getResourceException(int $id): ResourceException
    {
        $resourceException = $this->entityManager->getRepository(ResourceException::class)->find($id);

        if (!$resourceException) {
            throw new \InvalidArgumentException('Resource exception not found');
        }

        return $resourceException;
    }

    public function listResourceExceptions(Resource $resource, BaseFilterDTO $filters): array
    {
        return $this->resourceExceptionRepository->findByResourceFiltered($resource, $filters);
    }

    public function createResourceException(Resource $resource, ResourceExceptionDTO $resourceExceptionDTO): Resource
    {
        $resourceException = $this->hydrator->hydrate(new ResourceException(), $resourceExceptionDTO);

        $resource->addResourceException($resourceException);

        $this->entityManager->persist($resourceException);
        $this->entityManager->persist($resource);
        $this->entityManager->flush();

        return $resource;
    }

    public function updateResourceException(ResourceException $resourceException, ResourceExceptionDTO $resourceExceptionDTO): ResourceException
    {
        $this->hydrator->hydrate($resourceException, $resourceExceptionDTO);
        $this->entityManager->flush();

        return $resourceException;
    }

    public function deleteResourceException(ResourceException $resourceException): void
    {
        $this->entityManager->remove($resourceException);
        $this->entityManager->flush();
    }

    public function isUnavailable(Resource $resource, \DateTimeImmutable $dateTime): bool
    {
        foreach ($resource->getResourceExceptions() as $exception) {
            if ($exception->getDate()->format('d/m/Y') === $dateTime->format('d/m/Y')) {
                return true;
            }
        }

        return false;
    }
}
