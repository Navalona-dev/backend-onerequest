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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Repository\CategorieDomaineEntrepriseRepository;
use App\Controller\Api\DomaineEntrepriseByCategorieController;
use App\DataPersister\CategorieDomaineEntrepriseAddDataPersister;
use App\DataPersister\CategorieDomaineEntrepriseDeleteDataPersister;
use App\DataPersister\CategorieDomaineEntrepriseUpdateDataPersister;

#[ORM\Entity(repositoryClass: CategorieDomaineEntrepriseRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'categorie_domaine_entreprise:list']), 
        new Get(normalizationContext: ['groups' => 'categorie_domaine_entreprise:item']), 
        new Get(
            normalizationContext: ['groups' => 'site:item'],
            uriTemplate: '/categorie_domaine_entreprises/{id}/domaines',
            controller: DomaineEntrepriseByCategorieController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer les domaines d\'entreprise par categorie',
                    'description' => 'Cette opération récupère les domaines d\'entreprise par catégorie.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),          
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
        
        new Post( 
            processor: CategorieDomaineEntrepriseAddDataPersister::class,
        ),

        new Patch( 
            processor: CategorieDomaineEntrepriseUpdateDataPersister::class,
        ),

        new Delete( 
            processor: CategorieDomaineEntrepriseDeleteDataPersister::class,
        ),

    ]
)]
class CategorieDomaineEntreprise
{
    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, DomaineEntreprise>
     */
    #[ORM\OneToMany(targetEntity: DomaineEntreprise::class, mappedBy: 'categorieDomaineEntreprise')]
    private Collection $domaines;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    private ?string $nomEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['categorie_domaine_entreprise:list', 'categorie_domaine_entreprise:item'])]
    private ?string $descriptionEn = null;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
     * @return Collection<int, DomaineEntreprise>
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }

    public function addDomaine(DomaineEntreprise $domaine): static
    {
        if (!$this->domaines->contains($domaine)) {
            $this->domaines->add($domaine);
            $domaine->setCategorieDomaineEntreprise($this);
        }

        return $this;
    }

    public function removeDomaine(DomaineEntreprise $domaine): static
    {
        if ($this->domaines->removeElement($domaine)) {
            // set the owning side to null (unless already changed)
            if ($domaine->getCategorieDomaineEntreprise() === $this) {
                $domaine->setCategorieDomaineEntreprise(null);
            }
        }

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

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(?string $nomEn): static
    {
        $this->nomEn = $nomEn;

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
