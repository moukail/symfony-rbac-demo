<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Permission::class)]
    #[ORM\JoinTable(name:"roles_permissions")]
    #[ORM\JoinColumn(name:"role_id", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"permission_id", referencedColumnName:"id")]
    #[Groups(["user", "manager"])]
    private Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function clearPermissions(): void
    {
        $this->permissions->clear();
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }
        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
