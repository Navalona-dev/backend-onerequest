<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;  
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegionRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\DataPersister\RegionDataPersister;
use Doctrine\Common\Collections\Collection;
use App\DataPersister\RegionAddDataPersister;
use App\Controller\Api\SiteByRegionController;
use App\DataPersister\RegionDeleteDataPersister;
use App\DataPersister\RegionUpdateDataPersister;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\Api\CommuneByRegionController;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'region:list']), 
        new Get(normalizationContext: ['groups' => 'region:item']),           
        new Post(),
        new Patch(),
        new Delete(),

        new Get(
            normalizationContext: ['groups' => 'region:item'],
            uriTemplate: '/regions/{id}/sites',
            controller: SiteByRegionController::class,
            read: false, 
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer les sites par région',
                    'description' => 'Cette opération récupère les sites par région.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'region:item'],
            uriTemplate: '/regions/{id}/communes',
            controller: CommuneByRegionController::class,
            read: false, 
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer les communes par région',
                    'description' => 'Cette opération récupère les communes par région.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        
        new Post( 
            processor: RegionAddDataPersister::class,
        ),

        new Patch( 
            processor: RegionUpdateDataPersister::class,
        ),

        new Delete( 
            processor: RegionDeleteDataPersister::class,
        ),
        
    ]
)]
class Region
{
    #[Groups(['region:list', 'region:item', 'site:list', 'site:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['region:list', 'region:item', 'site:list', 'site:item'])]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Site>
     */
    ##[Groups(['region:list', 'region:item'])]
    #[ORM\OneToMany(targetEntity: Site::class, mappedBy: 'region')]
    private Collection $sites;

    /**
     * @var Collection<int, Commune>
     */
    #[ORM\OneToMany(targetEntity: Commune::class, mappedBy: 'region')]
    #[Groups(['region:list', 'region:item', 'site:list', 'site:item'])]
    private Collection $communes;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->communes = new ArrayCollection();
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
            $site->setRegion($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getRegion() === $this) {
                $site->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commune>
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): static
    {
        if (!$this->communes->contains($commune)) {
            $this->communes->add($commune);
            $commune->setRegion($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): static
    {
        if ($this->communes->removeElement($commune)) {
            // set the owning side to null (unless already changed)
            if ($commune->getRegion() === $this) {
                $commune->setRegion(null);
            }
        }

        return $this;
    }
}
