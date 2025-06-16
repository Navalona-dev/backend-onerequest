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
use Doctrine\Common\Collections\Collection;
use App\Repository\DomaineEntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DomaineEntrepriseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'domaine_entreprise:list']), 
        new Get(normalizationContext: ['groups' => 'domaine_entreprise:item']),            
        new Post(),
        new Patch(),
        new Delete(),
    ]
)]
class DomaineEntreprise
{
    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Entreprise>
     */
    #[ORM\OneToMany(targetEntity: Entreprise::class, mappedBy: 'domaineEntreprise')]
    private Collection $entreprise;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\ManyToOne(inversedBy: 'domaines')]
    private ?CategorieDomaineEntreprise $categorieDomaineEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    public function __construct()
    {
        $this->entreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

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
     * @return Collection<int, Entreprise>
     */
    public function getEntreprise(): Collection
    {
        return $this->entreprise;
    }

    public function addEntreprise(Entreprise $entreprise): static
    {
        if (!$this->entreprise->contains($entreprise)) {
            $this->entreprise->add($entreprise);
            $entreprise->setDomaineEntreprise($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): static
    {
        if ($this->entreprise->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getDomaineEntreprise() === $this) {
                $entreprise->setDomaineEntreprise(null);
            }
        }

        return $this;
    }

    public function getCategorieDomaineEntreprise(): ?CategorieDomaineEntreprise
    {
        return $this->categorieDomaineEntreprise;
    }

    public function setCategorieDomaineEntreprise(?CategorieDomaineEntreprise $categorieDomaineEntreprise): static
    {
        $this->categorieDomaineEntreprise = $categorieDomaineEntreprise;

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
}
