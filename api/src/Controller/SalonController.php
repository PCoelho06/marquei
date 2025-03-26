<?php

namespace App\Controller;


use App\Entity\Salon;
use App\Service\SalonService;
use App\Service\FilterService;
use App\DTO\Filters\SalonFilterDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/salons', name: 'client_salons_')]
final class SalonController extends AbstractController
{

    public function __construct(
        private SalonService $salonService,
        private FilterService $filterService
    ) {}

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $this->filterService->createDtoFromRequest(SalonFilterDTO::class, $request->query->all());
            $data = $this->salonService->searchSalons($filters);
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
                'salons' => array_map(fn(Salon $salon) => $salon->toArray(), $data['data']),
                'settings' => $data['settings'],
            ],
        ], JsonResponse::HTTP_OK);
    }
}
