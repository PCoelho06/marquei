<?php

namespace App\Controller;

use App\DTO\SalonDTO;
use App\Entity\Subscription;
use App\Service\SalonService;
use App\Service\StripeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/salons', name: 'salons_')]
final class SalonController extends AbstractController
{
    public function __construct(private SalonService $salonService, private StripeService $stripeService) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createSalon(#[MapRequestPayload(validationGroups: ['create'])] SalonDTO $salonDTO): JsonResponse
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

    #[Route('/{id}/business-hours', name: 'get_business_hours', methods: ['GET'])]
    public function getSalonBusinessHours(int $id): JsonResponse
    {
        try {
            $businessHours = $this->salonService->getSalonBusinessHours($id);
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
            'data' => $businessHours,
        ]);
    }

    #[Route('/{id}/services', name: 'get_services', methods: ['GET'])]
    public function getSalonServices(Request $request, int $id): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        try {
            $services = $this->salonService->getSalonServices($id, $page, $limit);
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
            'data' => $services,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
            ],
        ]);
    }

    #[Route('/{id}/employees', name: 'get_employees', methods: ['GET'])]
    public function getSalonEmployees(Request $request, int $id): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        try {
            $employees = $this->salonService->getSalonEmployees($id, $page, $limit);
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
            'data' => $employees,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
            ],
        ]);
    }

    #[Route('/{id}/machines', name: 'get_machines', methods: ['GET'])]
    public function getSalonMachines(Request $request, int $id): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        try {
            $machines = $this->salonService->getSalonMachines($id, $page, $limit);
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
            'data' => $machines,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
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
}
