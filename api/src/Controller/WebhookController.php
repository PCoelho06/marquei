<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Service\StripeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/webhook', name: 'webhook_')]
class WebhookController extends AbstractController
{
    public function __construct(private StripeService $stripeService) {}

    #[Route('/stripe', name: 'stripe', methods: ['POST'])]
    public function stripeWebhook(Request $request): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');
        $endpointSecret = $this->getParameter('stripe_webhook_secret');

        try {
            $this->stripeService->handleWebhook($payload, $sigHeader, $endpointSecret);

            return $this->json([
                'status' => 'success',
                'message' => 'Webhook processed'
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
