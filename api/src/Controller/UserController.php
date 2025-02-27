<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SalonService;
use App\Service\UserService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/user', name: 'user_')]
final class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private SalonService $salonService
    ) {}

    #[Route('/salons', name: 'salons', methods: ['GET'])]
    public function getUserSalons(#[CurrentUser] User $user): JsonResponse
    {
        $salons = $this->salonService->listUserSalons($user);

        return $this->json([
            'status' => 'success',
            'data' => $salons,
        ]);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getUserById(#[CurrentUser] ?User $user, int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUser($id);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'data' => $user->toArray(),
        ]);
    }
}
