<?php

namespace App\Controller\Admin;

use App\DTO\SalonDTO;
use App\Entity\Salon;
use App\Entity\Subscription;
use App\Service\SalonService;
use App\Service\FilterService;
use App\Service\StripeService;
use App\DTO\Filters\SalonFilterDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/admin/salons', name: 'admin_salons_')]
final class AdminSalonController extends AbstractController
{
    public function __construct(
        private SalonService $salonService,
        private StripeService $stripeService,
        private FilterService $filterService
    ) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['create'])] SalonDTO $salonDTO): JsonResponse
    {
        try {
            $salon = $this->salonService->createSalon($salonDTO);
        } catch (\Exception $e) {
            $statusCode = $e instanceof AccessDeniedException ? JsonResponse::HTTP_FORBIDDEN : JsonResponse::HTTP_BAD_REQUEST;
            return $this->json([
                'status' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                ],
            ], $statusCode);
        }

        return $this->json([
            'status' => 'success',
            'data' => $salon->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(int $id): JsonResponse
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

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateSalon(int $id, #[MapRequestPayload(validationGroups: ['update'])] SalonDTO $salonDTO): JsonResponse
    {
        try {
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
            'data' => $salon->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteSalon(int $id): JsonResponse
    {
        try {
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

    #[Route('/{id}/subscription', name: 'get_subscription', methods: ['GET'])]
    public function getSalonSubscription(int $id): JsonResponse
    {
        try {
            $subscription = $this->salonService->getSalonCurrentSubscription($id);
            $stripeSubscription = $this->stripeService->getStripeSubscription($subscription->getStripeSubscriptionId());
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
                'subscription' => $subscription ? $subscription->toArray() : new Subscription()->toArray(),
                'stripeSubscription' => $stripeSubscription,
            ],
        ]);
    }

    #[Route('/data', name: 'data', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        try {
            $data = $this->salonService->getData();
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
            'data' => $data,
        ]);
    }
}
