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

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(),  
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
     * @var Collection<int, DomaineEntreprise>
     */
    #[ORM\ManyToMany(targetEntity: DomaineEntreprise::class, mappedBy: 'entreprises')]
    private Collection $domaineEntreprises;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->domaineEntreprises = new ArrayCollection();
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
     * @return Collection<int, DomaineEntreprise>
     */
    public function getDomaineEntreprises(): Collection
    {
        return $this->domaineEntreprises;
    }

    public function addDomaineEntreprise(DomaineEntreprise $domaineEntreprise): static
    {
        if (!$this->domaineEntreprises->contains($domaineEntreprise)) {
            $this->domaineEntreprises->add($domaineEntreprise);
            $domaineEntreprise->addEntreprise($this);
        }

        return $this;
    }

    public function removeDomaineEntreprise(DomaineEntreprise $domaineEntreprise): static
    {
        if ($this->domaineEntreprises->removeElement($domaineEntreprise)) {
            $domaineEntreprise->removeEntreprise($this);
        }

        return $this;
    }


}
