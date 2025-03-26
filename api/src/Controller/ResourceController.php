<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\ResourceDTO;
use App\Entity\Resource;
use App\DTO\Filters\ResourceFilterDTO;
use App\Service\FilterService;
use App\Service\ResourceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/resources', name: 'resources_')]
class ResourceController extends AbstractController
{
    public function __construct(private ResourceService $resourceService, private FilterService $filterService) {}

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

    #[Route('/{id}', name: 'get', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
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

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(#[CurrentUser()] User $user, Request $request): JsonResponse
    {
        try {
            $filters = $this->filterService->createDtoFromRequest(ResourceFilterDTO::class, $request->query->all());
            $data = $this->resourceService->searchResources($user, $filters);
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
                'resources' => array_map(fn(Resource $resource) => $resource->toArray(), $data['data']),
                'settings' => $data['settings'],
            ],
        ]);
    }

    #[Route('/{type}', name: 'list_type', methods: ['GET'])]
    public function listByType(#[CurrentUser()] User $user, string $type): JsonResponse
    {
        try {
            $resources = $this->resourceService->getResourcesByType($user, $type);
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
            'data' => $resources,
        ]);
    }
}
