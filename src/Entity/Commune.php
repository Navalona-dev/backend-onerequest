<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;  
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommuneRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use App\DataPersister\CommuneAddDataPersister;
use Doctrine\Common\Collections\ArrayCollection;
use App\DataPersister\CommuneDeleteDataPersister;
use App\DataPersister\CommuneUpdateDataPersister;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'commune:list']), 
        new Get(normalizationContext: ['groups' => 'commune:item']),            
        new Post(),
        new Patch(),
        new Delete(),
        new Post( 
            processor: CommuneAddDataPersister::class,
        ),
        new Patch( 
            processor: CommuneUpdateDataPersister::class,
        ),
        new Delete( 
            processor: CommuneDeleteDataPersister::class,
        ),
    ]
)]
class Commune
{
    #[Groups(['commune:list', 'commune:item', 'region:list', 'region:item', 'site:list', 'site:item', 'traitement:list', 'traitement:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['commune:list', 'commune:item', 'region:list', 'region:item', 'site:list', 'site:item', 'traitement:list', 'traitement:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Groups(['commune:list', 'commune:item'])]
    #[ORM\ManyToOne(inversedBy: 'communes')]
    private ?Region $region = null;

    #[Groups(['commune:list', 'commune:item', 'region:list', 'region:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $district = null;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\OneToMany(targetEntity: Site::class, mappedBy: 'commune')]
    private Collection $sites;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): static
    {
        $this->district = $district;

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
            $site->setCommune($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getCommune() === $this) {
                $site->setCommune(null);
            }
        }

        return $this;
    }
}
