<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user", "manager"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["user", "manager"])]
    private ?string $email = null;

/*    #[ORM\ManyToMany(targetEntity: Role::class)]
    #[ORM\JoinTable(name:"user_roles")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    #[ORM\InverseJoinColumn(name:"role_id", referencedColumnName:"id")]
    private Collection $roles;*/

    #[ORM\ManyToOne(targetEntity:Role::class)]
    #[ORM\JoinColumn(nullable:false)]
    private Role $role;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["user", "manager"])]
    private ?string $password = null;

    private string $plainPassword;

    #[ORM\Column(length: 255)]
    #[Groups(["user", "manager"])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user", "manager"])]
    private ?string $last_name = null;

    #[ORM\Column]
    #[Groups(["user", "manager"])]
    private ?bool $inactive = false;

    public function __construct()
    {
        //$this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role->getName()];

/*        return array_map(function (Role $role){
           return $role->getName();
        }, $this->roles->toArray());*/
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;
        return $this;
    }

/*    public function setRoles(Collection $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(Role $role)
    {
        $this->roles->add($role);
    }

    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }*/

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function isInactive(): ?bool
    {
        return $this->inactive;
    }

    public function setInactive(bool $inactive): static
    {
        $this->inactive = $inactive;

        return $this;
    }
}
