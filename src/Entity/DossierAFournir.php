<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\DossierAFournirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DossierAFournirRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'dossier:list']), 
        new Get(normalizationContext: ['groups' => 'dossier:item']),            
        new Post(),
        new Patch(),
    ]
)]
class DossierAFournir
{
    #[Groups(['dossier:list', 'dossier:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, TypeDemande>
     */
    #[ORM\ManyToMany(targetEntity: TypeDemande::class, inversedBy: 'dossierAFournirs')]
    private Collection $typeDemande;

    #[ORM\Column(nullable: true)]
    #[Groups(['dossier:list', 'dossier:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['dossier:list', 'dossier:item'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dossier:list', 'dossier:item'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['dossier:list', 'dossier:item'])]
    private ?string $titleEn = null;

    public function __construct()
    {
        $this->typeDemande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, TypeDemande>
     */
    public function getTypeDemande(): Collection
    {
        return $this->typeDemande;
    }

    public function addTypeDemande(TypeDemande $typeDemande): static
    {
        if (!$this->typeDemande->contains($typeDemande)) {
            $this->typeDemande->add($typeDemande);
        }

        return $this;
    }

    public function removeTypeDemande(TypeDemande $typeDemande): static
    {
        $this->typeDemande->removeElement($typeDemande);

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(?string $titleEn): static
    {
        $this->titleEn = $titleEn;

        return $this;
    }
}
