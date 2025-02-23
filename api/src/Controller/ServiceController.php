<?php

namespace App\Controller;

use App\DTO\ServiceDTO;
use App\Service\ServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/services', name: 'services_')]
class ServiceController extends AbstractController
{
    public function __construct(private ServiceService $serviceService) {}

    #[Route('/', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $services = $this->serviceService->getServices();

        return $this->json([
            'status' => 'success',
            'data' => $services,
        ], Response::HTTP_OK);
    }

    #[Route('/', name: 'add', methods: ['POST'])]
    public function add(#[MapRequestPayload()] ServiceDTO $serviceDTO): JsonResponse
    {
        $service = $this->serviceService->createService($serviceDTO);

        return $this->json([
            'status' => 'success',
            'data' => $service->toArray(),
        ], Response::HTTP_CREATED);
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
