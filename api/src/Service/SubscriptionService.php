<?php

namespace App\Service;

use App\DTO\SubscriptionDTO;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Subscription as StripeSubscription;

class SubscriptionService
{
    public function __construct(private EntityManagerInterface $entityManager, private SubscriptionRepository $subscriptionRepository, private SalonService $salonService) {}

    public function getSubscription(string $subscriptionId): ?Subscription
    {
        $subscription = $this->subscriptionRepository->find($subscriptionId);

        if ($subscription === null) {
            throw new \InvalidArgumentException('Subscription not found');
        }

        return $subscription;
    }

    public function getSubscriptionByStripeSubscription($stripeSubscription): ?Subscription
    {
        if ($stripeSubscription === null) {
            throw new \InvalidArgumentException('Stripe Subscription not found');
        }

        $subscription = $this->subscriptionRepository->findOneBy(['stripeSubscriptionId' => $stripeSubscription->id]);

        if ($subscription === null) {
            throw new \InvalidArgumentException('Subscription not found');
        }

        return $subscription;
    }

    public function createSubscription(User $user, StripeSubscription $stripeSubscription, SubscriptionDTO $subscriptionDTO): Subscription
    {
        $salon = $this->salonService->getSalon($subscriptionDTO->salonId);

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setStripeSubscriptionId($stripeSubscription->id);
        $subscription->setStripePriceId($subscriptionDTO->priceId);
        $subscription->setSalon($salon);
        $subscription->setStartDate(new \DateTimeImmutable());
        $subscription->setStatus($stripeSubscription->status);
        $subscription->setStripeItemId($stripeSubscription->items->data[0]->id);

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        return $subscription;
    }

    public function updateSubscription($stripeSubscription): Subscription
    {
        $subscription = $this->getSubscriptionByStripeSubscription($stripeSubscription);

        if ($subscription) {
            $subscription->setStatus($stripeSubscription->status);
            $subscription->setStripePriceId($stripeSubscription->items->data[0]->price->id);
            $subscription->setStripeItemId($stripeSubscription->items->data[0]->id);

            if ($stripeSubscription->status === 'canceled') {
                $subscription->setEndDate(new \DateTimeImmutable());
            }

            $this->entityManager->flush();
        }

        return $subscription;
    }

    public function deleteSubscription(string $subscriptionId): void
    {
        $subscription = $this->subscriptionRepository->find($subscriptionId);

        if ($subscription) {
            $this->entityManager->remove($subscription);
            $this->entityManager->flush();
        }
    }
}
