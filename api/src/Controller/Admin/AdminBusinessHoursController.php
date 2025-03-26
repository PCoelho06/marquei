<?php

namespace App\Controller\Admin;

use App\Service\SalonService;
use App\Service\UserSalonService;
use App\Service\BusinessHoursService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/business-hours', name: 'business_hours_')]
final class AdminBusinessHoursController extends AbstractController
{
    public function __construct(private BusinessHoursService $businessHoursService, private SalonService $salonService, private UserSalonService $userSalonService) {}

    #[Route('/{id}', name: 'handle', methods: ['POST'])]
    public function handleBusinessHours(int $id, Request $request): JsonResponse
    {
        try {
            $salon = $this->salonService->getSalon($id);
            $this->userSalonService->checkUserIsSalonOwner($salon);
            $data = json_decode($request->getContent(), true);
            $businessHoursRanges = $this->businessHoursService->saveBusinessHours($salon, $data);
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
                'salon' => $salon->toArray(),
                'businessHoursRanges' => array_map(fn($businessHours) => $businessHours->toArray(), $businessHoursRanges),
            ],
        ]);
    }
}
