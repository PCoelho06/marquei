<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(\Symfony\Component\HttpFoundation\Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'data' => [
                'title' => 'Invalid credentials',
                'message' => 'Please check your email and password.',
            ],
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
