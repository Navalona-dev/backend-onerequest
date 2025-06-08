<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\ApiResource\Filter\RoleFilter;
use ApiPlatform\Metadata\GetCollection;
use App\DataPersister\AddUserDataPersister;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'user:list', 'enable_max_depth' => true]), 
        new Get(normalizationContext: ['groups' => 'user:item']),          
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
        new Post( 
            processor: AddUserDataPersister::class,
        ),
    ]
)]
#[ApiFilter(RoleFilter::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isSuperAdmin = null;

    #[Groups(['user:list', 'user:item'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Site $site = null;
    
    #[Groups(['user:list', 'user:item'])]
    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Privilege::class, mappedBy: 'users')]
    private Collection $privileges;

    public function __construct()
    {
        $this->privileges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
    
        // Garantie que chaque utilisateur a au moins ROLE_USER
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
    
        return array_unique($roles);
    }
    

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // Cette méthode est utilisée pour effacer des données sensibles.
        // Par exemple, si tu stockes un mot de passe en clair temporairement.
        // Ici on ne fait rien car ce n’est pas nécessaire.
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function isSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(?bool $isSuperAdmin): static
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    /**
     * @return Collection<int, Privilege>
     */
    public function getPrivileges(): Collection
    {
        return $this->privileges;
    }

    public function addPrivilege(Privilege $privilege): static
    {
        if (!$this->privileges->contains($privilege)) {
            $this->privileges->add($privilege);
            $privilege->addUser($this);
        }

        return $this;
    }

    public function removePrivilege(Privilege $privilege): static
    {
        if ($this->privileges->removeElement($privilege)) {
            $privilege->removeUser($this);
        }

        return $this;
    }

}
