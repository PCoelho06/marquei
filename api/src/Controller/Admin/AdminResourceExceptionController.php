<?php

namespace App\Controller\Admin;

use App\DTO\ResourceExceptionDTO;
use App\Entity\ResourceException;
use App\Service\ResourceExceptionService;
use App\Service\ResourceService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/admin/resources_exception', name: 'resources_exception_')]
class AdminResourceExceptionController extends AbstractController
{

    public function __construct(private ResourceExceptionService $resourceExceptionService, private ResourceService $resourceService) {}

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getResourceException(int $id): JsonResponse
    {
        try {
            $resourceException = $this->resourceExceptionService->getResourceException($id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        // Assuming the ResourceException entity has a toArray method
        return $this->json([
            'status' => 'success',
            'resourceException' => $resourceException->toArray(),
        ]);
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function listResourceExceptions(#[MapRequestPayload()] int $id): JsonResponse
    {
        try {
            $resource = $this->resourceService->getResource($id);
            $resourceExceptions = $this->resourceExceptionService->listResourceExceptions($resource);
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
            'resourceExceptions' => array_map(fn($exception) => $exception->toArray(), $resourceExceptions),
        ]);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createResourceException(#[MapRequestPayload()] ResourceExceptionDTO $resourceExceptionDTO): JsonResponse
    {
        try {
            $resource = $this->resourceExceptionService->createResourceException($resourceExceptionDTO);
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
    public function updateResourceException(ResourceException $resourceException, #[MapRequestPayload()] ResourceExceptionDTO $resourceExceptionDTO): JsonResponse
    {
        try {
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
    public function deleteResourceException(ResourceException $resourceException): JsonResponse
    {
        try {
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
