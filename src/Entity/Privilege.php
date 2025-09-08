<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PrivilegeRepository;
use Doctrine\Common\Collections\Collection;
use App\DataPersister\PrivilegeAddDataPersister;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\DataPersister\PrivilegeUpdateDataPersister;

#[ORM\Entity(repositoryClass: PrivilegeRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'privilege:list']), 
        new Get(normalizationContext: ['groups' => 'privilege:item']),           
        new Post(),
        new Patch(),
        new Delete(),
        new Post( 
            processor: PrivilegeAddDataPersister::class,
        ),
        new Patch( 
            processor: PrivilegeUpdateDataPersister::class,
        ),
    ]
)]
class Privilege
{
    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    /**
     * @var Collection<int, Permission>
     */
    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    #[ORM\ManyToMany(targetEntity: Permission::class, mappedBy: 'privileges')]
    private Collection $permissions;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'privileges')]
    private Collection $users;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    private ?string $libelleFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    private ?string $libelleEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['privilege:list', 'privilege:item', 'user:list', 'user:item'])]
    private ?string $descriptionEn = null;

    /**
     * @var Collection<int, NiveauHierarchique>
     */
    #[ORM\OneToMany(targetEntity: NiveauHierarchique::class, mappedBy: 'privilege')]
    private Collection $niveauHierarchiques;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->niveauHierarchiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->addPrivilege($this);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        if ($this->permissions->removeElement($permission)) {
            $permission->removePrivilege($this);
        }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addPrivilege($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removePrivilege($this);
        }

        return $this;
    }

    public function getLibelleFr(): ?string
    {
        return $this->libelleFr;
    }

    public function setLibelleFr(?string $libelleFr): static
    {
        $this->libelleFr = $libelleFr;

        return $this;
    }

    public function getLibelleEn(): ?string
    {
        return $this->libelleEn;
    }

    public function setLibelleEn(?string $libelleEn): static
    {
        $this->libelleEn = $libelleEn;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(?string $descriptionEn): static
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * @return Collection<int, NiveauHierarchique>
     */
    public function getNiveauHierarchiques(): Collection
    {
        return $this->niveauHierarchiques;
    }

    public function addNiveauHierarchique(NiveauHierarchique $niveauHierarchique): static
    {
        if (!$this->niveauHierarchiques->contains($niveauHierarchique)) {
            $this->niveauHierarchiques->add($niveauHierarchique);
            $niveauHierarchique->setPrivilege($this);
        }

        return $this;
    }

    public function removeNiveauHierarchique(NiveauHierarchique $niveauHierarchique): static
    {
        if ($this->niveauHierarchiques->removeElement($niveauHierarchique)) {
            // set the owning side to null (unless already changed)
            if ($niveauHierarchique->getPrivilege() === $this) {
                $niveauHierarchique->setPrivilege(null);
            }
        }

        return $this;
    }
}
