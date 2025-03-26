<?php

namespace App\Controller;

use App\DTO\ServiceDTO;
use App\Entity\Service;
use App\DTO\Filters\ServiceFilterDTO;
use App\Service\FilterService;
use App\Service\ServiceService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/services', name: 'services_')]
class ServiceController extends AbstractController
{
    public function __construct(private ServiceService $serviceService, private FilterService $filterService) {}

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $this->filterService->createDtoFromRequest(ServiceFilterDTO::class, $request->query->all());
            $data = $this->serviceService->searchServices($filters);
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
                'services' => array_map(fn(Service $service) => $service->toArray(), $data['data']),
                'settings' => $data['settings'],
            ],
        ], Response::HTTP_OK);
    }

    #[Route('/', name: 'add', methods: ['POST'])]
    public function add(#[MapRequestPayload()] ServiceDTO $serviceDTO): JsonResponse
    {
        try {
            $service = $this->serviceService->createService($serviceDTO);
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
            'data' => $service->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        try {
            $service = $this->serviceService->getService($id);
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
            'data' => $service->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(#[MapRequestPayload()] ServiceDTO $serviceDTO, int $id): JsonResponse
    {
        try {
            $service = $this->serviceService->updateService($serviceDTO, $id);
        } catch (\Exception $e) {
            $statusCode = $e instanceof \InvalidArgumentException ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_FORBIDDEN;
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], $statusCode);
        }

        return $this->json([
            'status' => 'success',
            'data' => $service->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->serviceService->deleteService($id);
        } catch (\Exception $e) {
            $statusCode = $e instanceof \InvalidArgumentException ? JsonResponse::HTTP_NOT_FOUND : JsonResponse::HTTP_FORBIDDEN;
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], $statusCode);
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'message' => 'Prestação de serviço removida com sucesso',
            ],
        ]);
    }
}
