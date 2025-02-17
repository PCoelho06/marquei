<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class BusinessHoursDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public int $dayOfWeek;
    #[Assert\NotBlank(groups: ['create'])]
    public bool $isOpen;
    #[Assert\NotBlank(groups: ['create'])]
    public array $timeRanges;

    public function __construct(int $dayOfWeek, bool $isOpen, array $timeRanges)
    {
        $this->dayOfWeek = $dayOfWeek;
        $this->isOpen = $isOpen;
        $this->timeRanges = $timeRanges;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['day'],
            $data['isOpen'],
            $data['timeRanges'],
        );
    }

    public function toArray(): array
    {
        return [
            'dayOfWeek' => $this->dayOfWeek,
            'isOpen' => $this->isOpen,
            'timeRanges' => $this->timeRanges,
        ];
    }
}
