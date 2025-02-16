<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private JWTTokenManagerInterface $JWTManager, private RefreshTokenGeneratorInterface $refreshTokenGenerator, private ParameterBagInterface $params) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, $this->params->get('gesdinet_jwt_refresh_token.ttl'));

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'access_token' => $this->JWTManager->create($user),
                'refresh_token' => $refreshToken->getRefreshToken(),
                'user' => $user->toArray(),
            ],
        ]);
    }
}
