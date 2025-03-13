<?php
// src/Controller/SubscriptionController.php
namespace App\Controller;

use App\Entity\User;
use App\DTO\SubscriptionDTO;
use App\Service\StripeService;
use App\Service\SubscriptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/subscriptions', name: 'subscriptions_')]
class SubscriptionController extends AbstractController
{
    public function __construct(private StripeService $stripeService, private SubscriptionService $subscriptionService) {}

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createSubscription(#[CurrentUser] User $user, #[MapRequestPayload(validationGroups: ['create'])] SubscriptionDTO $subscriptionDTO): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            [$stripeSubscription, $subscription] = $this->stripeService->createStripeSubscription($user, $subscriptionDTO);

            return $this->json([
                'status' => 'success',
                'data' => [
                    'subscriptionId' => $subscription->getId(),
                    'stripeSubscriptionId' => $stripeSubscription->id,
                    'clientSecret' => $stripeSubscription->latest_invoice->payment_intent->client_secret,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/switch', name: 'switch', methods: ['PUT'])]
    public function switchSubscription(#[CurrentUser] User $user, #[MapRequestPayload(validationGroups: ['switch'])] SubscriptionDTO $subscriptionDTO): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            [$stripeSubscription, $subscription] = $this->stripeService->switchStripeSubscription($subscriptionDTO);

            return $this->json([
                'status' => 'success',
                'data' => [
                    'subscription' => $subscription->toArray(),
                    'stripeSubscription' => $stripeSubscription,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'cancel', methods: ['DELETE'])]
    public function cancelSubscription(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $stripeSubscriptionId = $data['stripeSubscriptionId'] ?? null;

        try {
            $this->subscriptionService->deleteSubscription($id);
            $this->stripeService->deleteStripeSubscription($stripeSubscriptionId);

            return $this->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
