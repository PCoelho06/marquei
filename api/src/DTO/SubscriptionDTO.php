<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class SubscriptionDTO
{
    #[Assert\NotBlank(groups: ['create', 'switch'])]
    public ?string $priceId = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $salonId = null;

    #[Assert\NotBlank(groups: ['switch'])]
    public ?string $stripeSubscriptionId = null;

    #[Assert\NotBlank(groups: ['switch'])]
    public ?string $stripeItemId = null;

    public function __construct(?string $priceId, ?string $salonId, ?string $stripeSubscriptionId, ?string $stripeItemId)
    {
        $this->priceId = $priceId;
        $this->salonId = $salonId;
        $this->stripeSubscriptionId = $stripeSubscriptionId;
        $this->stripeItemId = $stripeItemId;
    }
}
