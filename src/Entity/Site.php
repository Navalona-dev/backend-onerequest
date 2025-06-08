<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;   
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use App\Controller\Api\SiteToggleController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'site:list']), 
        new Get(normalizationContext: ['groups' => 'site:item']),          
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
        new Post(
            uriTemplate: '/sites/{id}/toggle-active',
            controller: SiteToggleController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Activer/Désactiver un site',
                    'description' => 'Cette opération active ou désactive un site existant.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
    ],
)]
class Site
{
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?Entreprise $entreprise = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    /**
     * @var Collection<int, CodeCouleur>
     */
    #[ORM\OneToMany(targetEntity: CodeCouleur::class, mappedBy: 'site')]
    private Collection $codeCouleurs;

    #[ORM\Column(nullable: true)]
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, User>
     */
   
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'site')]
    private Collection $users;

    public function __construct()
    {
        $this->codeCouleurs = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

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

    /**
     * @return Collection<int, CodeCouleur>
     */
    public function getCodeCouleurs(): Collection
    {
        return $this->codeCouleurs;
    }

    public function addCodeCouleurs(CodeCouleur $codeCouleurs): static
    {
        if (!$this->codeCouleurs->contains($codeCouleurs)) {
            $this->codeCouleurs->add($codeCouleurs);
            $codeCouleurs->setSite($this);
        }

        return $this;
    }

    public function removeCodeCouleurs(CodeCouleur $codeCouleurs): static
    {
        if ($this->codeCouleurs->removeElement($codeCouleurs)) {
            // set the owning side to null (unless already changed)
            if ($codeCouleurs->getSite() === $this) {
                $codeCouleurs->setSite(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $user->setSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSite() === $this) {
                $user->setSite(null);
            }
        }

        return $this;
    }
}
