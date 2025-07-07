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
use App\Repository\TypeDemandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\DataPersister\TypeDemandeAddDataPersister;
use Symfony\Component\Serializer\Attribute\Groups;
use App\DataPersister\TypeDemandeUpdateDataPersister;

#[ORM\Entity(repositoryClass: TypeDemandeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'type_demande:list']), 
        new Get(normalizationContext: ['groups' => 'type_demande:item']),           
        new Post(),
        new Patch(),
        new Delete(),

        new Post( 
            processor: TypeDemandeAddDataPersister::class,
        ),
        new Patch( 
            processor: TypeDemandeUpdateDataPersister::class,
        ),
    ],
    paginationEnabled: false
)]
class TypeDemande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'typeDemandes')]
    #[Groups(['type_demande:list', 'type_demande:item'])]
    private ?DomaineEntreprise $domaine = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'type')]
    private Collection $demandes;

    /**
     * @var Collection<int, DossierAFournir>
     */
    #[ORM\ManyToMany(targetEntity: DossierAFournir::class, mappedBy: 'typeDemande')]
    private Collection $dossierAFournirs;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->dossierAFournirs = new ArrayCollection();
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


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

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

    public function getDomaine(): ?DomaineEntreprise
    {
        return $this->domaine;
    }

    public function setDomaine(?DomaineEntreprise $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setType($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getType() === $this) {
                $demande->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DossierAFournir>
     */
    public function getDossierAFournirs(): Collection
    {
        return $this->dossierAFournirs;
    }

    public function addDossierAFournir(DossierAFournir $dossierAFournir): static
    {
        if (!$this->dossierAFournirs->contains($dossierAFournir)) {
            $this->dossierAFournirs->add($dossierAFournir);
            $dossierAFournir->addTypeDemande($this);
        }

        return $this;
    }

    public function removeDossierAFournir(DossierAFournir $dossierAFournir): static
    {
        if ($this->dossierAFournirs->removeElement($dossierAFournir)) {
            $dossierAFournir->removeTypeDemande($this);
        }

        return $this;
    }

}
