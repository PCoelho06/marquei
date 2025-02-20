<?php

namespace App\Security;

use App\Repository\RefreshTokenRepository;
use App\Service\RefreshTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JWTTokenManagerInterface $JWTManager,
        private EntityManagerInterface $entityManager,
        private RefreshTokenRepository $refreshTokenRepository,
        private RefreshTokenService $refreshTokenService,
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        $actualRefreshToken = $this->refreshTokenRepository->findOneBy(['user' => $user]);
        if ($actualRefreshToken) {
            $this->refreshTokenService->revokeRefreshToken($actualRefreshToken->getToken());
        }

        $refreshToken = $this->refreshTokenService->createRefreshToken($user);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $this->JWTManager->create($user),
                'refresh_token' => $refreshToken->getToken(),
                'user' => $user->toArray(),
            ],
        ]);
    }
}
