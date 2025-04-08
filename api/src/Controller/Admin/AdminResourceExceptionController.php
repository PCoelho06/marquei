<?php

namespace App\Controller\Admin;

use App\Entity\Resource;
use Psr\Log\LoggerInterface;
use App\Service\FilterService;
use App\Service\ResourceService;
use App\DTO\ResourceExceptionDTO;
use App\Entity\ResourceException;
use App\Service\UserSalonService;
use App\DTO\Filters\BaseFilterDTO;
use App\Service\ResourceExceptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/admin/{resourceId}/resources_exception', name: 'resources_exception_')]
class AdminResourceExceptionController extends AbstractController
{

    public function __construct(
        private UserSalonService $userSalonService,
        private ResourceExceptionService $resourceExceptionService,
        private ResourceService $resourceService,
        private FilterService $filterService,
    ) {}

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getResourceException(#[MapEntity(expr: 'repository.find(resourceId)')] Resource $resource, ResourceException $resourceException): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'resourceException' => $resourceException->toArray(),
        ]);
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function listResourceExceptions(#[MapEntity(expr: 'repository.find(resourceId)')] Resource $resource, Request $request): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
            $filters = $this->filterService->createDtoFromRequest(BaseFilterDTO::class, $request->query->all());
            $data = $this->resourceExceptionService->listResourceExceptions($resource, $filters);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'resourceExceptions' => array_map(fn($exception) => $exception->toArray(), $data['data']),
                'settings' => $data['settings'],
            ]
        ]);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createResourceException(#[MapEntity(expr: 'repository.find(resourceId)')] Resource $resource, #[MapRequestPayload()] ResourceExceptionDTO $resourceExceptionDTO): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
            $resource = $this->resourceExceptionService->createResourceException($resource, $resourceExceptionDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'resource' => $resource->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateResourceException(#[MapEntity(expr: 'repository.find(resourceId)')] Resource $resource, ResourceException $resourceException, #[MapRequestPayload()] ResourceExceptionDTO $resourceExceptionDTO): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
            $resourceException = $this->resourceExceptionService->updateResourceException($resourceException, $resourceExceptionDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'resourceException' => $resourceException->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteResourceException(#[MapEntity(expr: 'repository.find(resourceId)')] Resource $resource, ResourceException $resourceException): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
            $this->resourceExceptionService->deleteResourceException($resourceException);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Resource exception deleted successfully',
        ]);
    }
}
