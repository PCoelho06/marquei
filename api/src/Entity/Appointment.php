<?php

namespace App\Entity;

use App\Model\AppointmentStatusEnum;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salon $salon = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $resource = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startsAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endsAt = null;

    #[ORM\Column(enumType: AppointmentStatusEnum::class)]
    private ?AppointmentStatusEnum $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getSalon(): ?Salon
    {
        return $this->salon;
    }

    public function setSalon(?Salon $salon): static
    {
        $this->salon = $salon;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeImmutable $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeImmutable $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getStatus(): ?AppointmentStatusEnum
    {
        return $this->status;
    }

    public function setStatus(AppointmentStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'client' => $this->getClient()->toArrayClient(),
            'salon' => $this->getSalon()->toArray(),
            'resource' => $this->getResource()->toArray(),
            'service' => $this->getService()->toArray(),
            'startsAt' => $this->getStartsAt()->format('d/m/Y H:i'),
            'endsAt' => $this->getEndsAt()->format('d/m/Y H:i'),
            'status' => $this->getStatus()->value,
            'createdAt' => $this->getCreatedAt()->format('d/m/Y H:i'),
            'updatedAt' => $this->getUpdatedAt()->format('d/m/Y H:i'),
        ];
    }

    public function toArraySimple(): array
    {
        return [
            'id' => $this->getId(),
            'client' => $this->getClient()->toArrayClient(),
            'salon' => $this->getSalon()->toArraySimple(),
            'resource' => $this->getResource()->toArraySimple(),
            'service' => $this->getService()->toArraySimple(),
            'startsAt' => $this->getStartsAt()->format('d/m/Y H:i'),
            'endsAt' => $this->getEndsAt()->format('d/m/Y H:i'),
            'status' => $this->getStatus()->value,
        ];
    }
}
