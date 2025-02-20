<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\SalonDTO;
use App\Service\SalonService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/salons', name: 'salons_')]
final class SalonController extends AbstractController
{
    public function __construct(private SalonService $salonService) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createSalon(#[MapRequestPayload(validationGroups: ['create'])] SalonDTO $salonDTO): JsonResponse
    {
        $salon = $this->salonService->createSalon($salonDTO);

        return $this->json([
            'status' => 'success',
            'data' => $salon->toArray(),
        ]);
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function listSalons(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $salons = $this->salonService->listSalons($page, $limit);

        return $this->json([
            'status' => 'success',
            'data' => $salons,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
            ],
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getSalon(int $id): JsonResponse
    {
        try {
            $salon = $this->salonService->getSalon($id);
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
            'data' => $salon->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateSalon(int $id, #[MapRequestPayload(validationGroups: ['update'])] SalonDTO $salonDTO): JsonResponse
    {
        try {
            $this->salonService->checkUserIsSalonOwner($id);
            $salon = $this->salonService->updateSalon($id, $salonDTO);
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
            'data' => $salon,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteSalon(int $id): JsonResponse
    {
        try {
            $this->salonService->checkUserIsSalonOwner($id);
            $this->salonService->deleteSalon($id);
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
                'message' => 'Salão removido com sucesso',
            ],
        ]);
    }
}
