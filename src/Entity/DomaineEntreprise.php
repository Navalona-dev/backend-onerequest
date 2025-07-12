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
use App\DataPersister\DomaineEntrepriseAddDataPersister;
use App\DataPersister\DomaineEntrepriseDeleteDataPersister;
use App\DataPersister\DomaineEntrepriseUpdateDataPersister;

#[ORM\Entity(repositoryClass: DomaineEntrepriseRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'domaine_entreprise:list']), 
        new Get(normalizationContext: ['groups' => 'domaine_entreprise:item']),            
        new Post(),
        new Patch(),
        new Delete(),

        new Post( 
            processor: DomaineEntrepriseAddDataPersister::class,
        ),

        new Patch( 
            processor: DomaineEntrepriseUpdateDataPersister::class,
        ),

        new Delete( 
            processor: DomaineEntrepriseDeleteDataPersister::class,
        ),
    ]
)]
class DomaineEntreprise
{
    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item', 'type_demande:list', 'type_demande:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item', 'type_demande:list', 'type_demande:item'])]
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

    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    #[ORM\ManyToOne(inversedBy: 'domaines')]
    private ?CategorieDomaineEntreprise $categorieDomaineEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    /**
     * @var Collection<int, Entreprise>
     */
    #[ORM\ManyToMany(targetEntity: Entreprise::class, inversedBy: 'domaineEntreprises')]
    private Collection $entreprises;

    /**
     * @var Collection<int, TypeDemande>
     */
    #[ORM\OneToMany(targetEntity: TypeDemande::class, mappedBy: 'domaine')]
    private Collection $typeDemandes;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item', 'type_demande:list', 'type_demande:item'])]
    private ?string $libelleEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['domaine_entreprise:list', 'domaine_entreprise:item'])]
    private ?string $descriptionEn = null;

    public function __construct()
    {
        $this->entreprise = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->typeDemandes = new ArrayCollection();
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

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): static
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises->add($entreprise);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): static
    {
        $this->entreprises->removeElement($entreprise);

        return $this;
    }

    /**
     * @return Collection<int, TypeDemande>
     */
    public function getTypeDemandes(): Collection
    {
        return $this->typeDemandes;
    }

    public function addTypeDemande(TypeDemande $typeDemande): static
    {
        if (!$this->typeDemandes->contains($typeDemande)) {
            $this->typeDemandes->add($typeDemande);
            $typeDemande->setDomaine($this);
        }

        return $this;
    }

    public function removeTypeDemande(TypeDemande $typeDemande): static
    {
        if ($this->typeDemandes->removeElement($typeDemande)) {
            // set the owning side to null (unless already changed)
            if ($typeDemande->getDomaine() === $this) {
                $typeDemande->setDomaine(null);
            }
        }

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

}
