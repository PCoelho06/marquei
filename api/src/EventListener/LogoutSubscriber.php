<?php

namespace App\EventListener;

use App\Service\AuthenticationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private AuthenticationService $authenticationService
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if (null !== $token) {
            $user = $token->getUser();

            $this->authenticationService->logout($user);
        }

        $response = new JsonResponse([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ]);
        $event->setResponse($response);
    }
}
