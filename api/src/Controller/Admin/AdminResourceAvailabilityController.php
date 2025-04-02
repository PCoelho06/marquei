<?php

namespace App\Controller\Admin;

use App\Entity\Resource;
use App\Service\UserSalonService;
use App\Service\ResourceAvailabilityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/admin/resources_availability', name: 'resources_availability_')]
class AdminResourceAvailabilityController extends AbstractController
{
    public function __construct(private UserSalonService $userSalonService, private ResourceAvailabilityService $resourceAvailabilityService) {}

    #[Route('/{id}', name: 'create', methods: ['POST'])]
    public function manageResourceDisponibilities(Resource $resource, Request $request): JsonResponse
    {
        try {
            $this->userSalonService->checkUserIsSalonOwner($resource->getSalon());
            $data = json_decode($request->getContent(), true);
            $this->resourceAvailabilityService->setResourceAvailabilities($resource, $data);
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
}
