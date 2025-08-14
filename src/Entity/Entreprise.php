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
use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Controller\Api\DomaineByEntrepriseController;
use App\Controller\Api\CategorieByEntrepriseController;
use App\Controller\Api\TypeDemandeByEntrepriseController;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(),  
        new Get(
            normalizationContext: ['groups' => 'entreprise:item'],
            uriTemplate: '/entreprises/domaines',
            controller: DomaineByEntrepriseController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de domaine par entreprise',
                    'description' => 'Cette opération récupère la liste de domaine par entreprise.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Get(
            normalizationContext: ['groups' => 'entreprise:item'],
            uriTemplate: '/entreprises/categories',
            controller: CategorieByEntrepriseController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de catégorie par entreprise',
                    'description' => 'Cette opération récupère la liste de catégorie par entreprise.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Get(
            normalizationContext: ['groups' => 'entreprise:item'],
            uriTemplate: '/entreprises/type-demandes',
            controller: TypeDemandeByEntrepriseController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de type de demande par entreprise',
                    'description' => 'Cette opération récupère la liste de type de demande par entreprise.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Get(),            
        new Post(),
        new Patch(),
        new Delete(),
    ]
)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\OneToMany(targetEntity: Site::class, mappedBy: 'entreprise')]
    private Collection $sites;

    /**
     * @var Collection<int, CategorieDomaineEntreprise>
     */
    #[ORM\ManyToMany(targetEntity: CategorieDomaineEntreprise::class, mappedBy: 'entreprises')]
    private Collection $categorieDomaineEntreprises;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->categorieDomaineEntreprises = new ArrayCollection();
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
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
            $site->setEntreprise($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getEntreprise() === $this) {
                $site->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategorieDomaineEntreprise>
     */
    public function getCategorieDomaineEntreprises(): Collection
    {
        return $this->categorieDomaineEntreprises;
    }

    public function addCategorieDomaineEntreprise(CategorieDomaineEntreprise $categorieDomaineEntreprise): static
    {
        if (!$this->categorieDomaineEntreprises->contains($categorieDomaineEntreprise)) {
            $this->categorieDomaineEntreprises->add($categorieDomaineEntreprise);
            $categorieDomaineEntreprise->addEntreprise($this);
        }

        return $this;
    }

    public function removeCategorieDomaineEntreprise(CategorieDomaineEntreprise $categorieDomaineEntreprise): static
    {
        if ($this->categorieDomaineEntreprises->removeElement($categorieDomaineEntreprise)) {
            $categorieDomaineEntreprise->removeEntreprise($this);
        }

        return $this;
    }


}
