<?php

namespace App\Controller;

use App\DTO\ResourceDTO;
use App\Service\ResourceService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/resources', name: 'resources_')]
class ResourceController extends AbstractController
{
    public function __construct(private ResourceService $resourceService) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function add(#[MapRequestPayload()] ResourceDTO $resourceDTO): JsonResponse
    {
        try {
            $resource = $this->resourceService->createResource($resourceDTO);
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
            'data' => $resource->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        try {
            $resource = $this->resourceService->getResource($id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => $resource->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(#[MapRequestPayload()] ResourceDTO $resourceDTO, int $id): JsonResponse
    {
        try {
            $resource = $this->resourceService->updateResource($resourceDTO, $id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => $resource->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->resourceService->deleteResource($id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'message' => 'Recurso removido com sucesso',
            ],
        ]);
    }
}
