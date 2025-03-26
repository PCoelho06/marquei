<?php

namespace App\Security;

use App\Service\SalonService;
use App\DTO\Filters\SalonFilterDTO;
use App\Service\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use App\Service\UserSalonService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private UserSalonService $userSalonService,
        private EntityManagerInterface $entityManager,
        private RefreshTokenRepository $refreshTokenRepository,
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $this->authenticationService->generateJWT($user),
                'user' => $user->toArray(),
                'hasSalons' => $this->userSalonService->hasSalons($user),
            ],
        ]);
    }
}
