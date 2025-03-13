<?php

namespace App\Service;

use App\DTO\SubscriptionDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Price as StripePrice;
use Stripe\Customer as StripeCustomer;
use Stripe\Subscription as StripeSubscription;
use Stripe\SubscriptionItem as StripeSubscriptionItem;
use Stripe\PaymentMethod as StripePaymentMethod;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeService
{
    private $entityManager;
    private $params;
    private $subscriptionService;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params, SubscriptionService $subscriptionService)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
        $this->subscriptionService = $subscriptionService;

        Stripe::setApiKey($this->params->get('stripe_secret_key'));
    }

    public function getStripeSubscription(string $subscriptionId): StripeSubscription
    {
        return StripeSubscription::retrieve($subscriptionId);
    }

    public function createStripeCustomer(User $user): StripeCustomer
    {
        $customer = StripeCustomer::create([
            'email' => $user->getEmail(),
        ]);

        $user->setStripeCustomerId($customer->id);
        $this->entityManager->flush();

        return $customer;
    }

    public function createStripeSubscription(User $user, SubscriptionDTO $subscriptionDTO): array
    {
        $customer = $user->getStripeCustomerId() ? StripeCustomer::retrieve($user->getStripeCustomerId()) : $this->createStripeCustomer($user);

        $stripeSubscription = StripeSubscription::create([
            'customer' => $customer->id,
            'items' => [[
                'price' => $subscriptionDTO->priceId,
            ]],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        $subscription = $this->subscriptionService->createSubscription($user, $stripeSubscription, $subscriptionDTO);

        return [$stripeSubscription, $subscription];
    }

    public function switchStripeSubscription(SubscriptionDTO $subscriptionDTO): array
    {
        $stripeSubscription = StripeSubscription::update(
            $subscriptionDTO->stripeSubscriptionId,
            ['items' => [
                [
                    'price' => $subscriptionDTO->priceId,
                ],
                [
                    'id' => $subscriptionDTO->stripeItemId,
                    'deleted' => true,
                ],
            ]],
            ['proration_behavior' => 'always_invoice']
        );

        $subscription = $this->subscriptionService->updateSubscription($stripeSubscription);

        return [$stripeSubscription, $subscription];
    }

    public function deleteStripeSubscription(string $subscriptionId): void
    {
        $subscription = StripeSubscription::retrieve($subscriptionId);
        $subscription->delete();
    }

    public function handleWebhook($payload, $sigHeader, $endpointSecret): void
    {
        $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

        // Handle the event
        switch ($event->type) {
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $stripeSubscription = $event->data->object;
                $this->subscriptionService->updateSubscription($stripeSubscription);
                break;
        }
    }
}
