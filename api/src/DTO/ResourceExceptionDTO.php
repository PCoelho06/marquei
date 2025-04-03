<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ResourceExceptionDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public \DateTimeInterface $date;
    #[Assert\NotBlank(groups: ['create'])]
    public \DateTimeInterface $startTime;
    #[Assert\NotBlank(groups: ['create'])]
    public \DateTimeInterface $endTime;
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Positive(groups: ['create'])]
    public int $resourceId;

    public function __construct(string $date, string $startTime, string $endTime, int $resourceId)
    {
        $this->date = new \DateTime($date);
        $this->startTime = new \DateTime($startTime);
        $this->endTime = new \DateTime($endTime);
        $this->resourceId = $resourceId;
    }
}
