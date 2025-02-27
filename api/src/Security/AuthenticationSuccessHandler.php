<?php

namespace App\Security;

use App\Service\SalonService;
use App\Service\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private SalonService $salonService,
        private EntityManagerInterface $entityManager,
        private RefreshTokenRepository $refreshTokenRepository,
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        $salons = $this->salonService->listUserSalons($user);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $this->authenticationService->generateJWT($user),
                'user' => $user->toArray(),
                'hasSalons' => !!count($salons),
            ],
        ]);
    }
}
