<?php

namespace App\Entity;

use App\Entity\Permission;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserSalonPermissionRepository;

#[ORM\Entity(repositoryClass: UserSalonPermissionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserSalonPermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: UserSalon::class, inversedBy: "permissions")]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserSalon $userSalon = null;

    #[ORM\ManyToOne(targetEntity: Permission::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Permission $permission = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserSalon(): ?UserSalon
    {
        return $this->userSalon;
    }

    public function setUserSalon(?UserSalon $userSalon): self
    {
        $this->userSalon = $userSalon;

        return $this;
    }

    public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    public function setPermission(?Permission $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
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
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'userSalon' => $this->userSalon->toArray(),
            'permission' => $this->permission->toArray(),
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
