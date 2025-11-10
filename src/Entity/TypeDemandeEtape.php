<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\TypeDemandeEtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\DataPersister\EtapeTypeDemandeAddDataPersister;
use App\DataPersister\EtapeTypeDemandeDeleteDataPersister;
use App\DataPersister\EtapeTypeDemandeUpdateDataPersister;

#[ORM\Entity(repositoryClass: TypeDemandeEtapeRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'type_demande_etape:list']),  
        new Get(normalizationContext: ['groups' => 'type_demande_etape:item']),            
        new Post( 
            processor: EtapeTypeDemandeAddDataPersister::class,
        ),
        new Put(),
        new Patch( 
            processor: EtapeTypeDemandeUpdateDataPersister::class,
        ),
        new Delete( 
            processor: EtapeTypeDemandeDeleteDataPersister::class,
        ),
        
    ]
)]
class TypeDemandeEtape
{

    const STATUT = [
        1 => "En cours",
        2 => 'Fait'
    ];

    const STATUT_EN = [
        1 => "In progress",
        2 => 'Done'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'typeDemandeEtapes')]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?TypeDemande $typeDemande = null;

    #[ORM\ManyToOne(inversedBy: 'typeDemandeEtapes')]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?Site $site = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $ordre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $statutInitial = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $titleEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'typeDemandeEtapes')]
    #[Groups(['type_demande_etape:list', 'type_demande_etape:item'])]
    private ?NiveauHierarchique $niveauHierarchique = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'etapeDemande')]
    private Collection $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDemande(): ?TypeDemande
    {
        return $this->typeDemande;
    }

    public function setTypeDemande(?TypeDemande $typeDemande): static
    {
        $this->typeDemande = $typeDemande;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

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

    public function getOrdre(): ?string
    {
        return $this->ordre;
    }

    public function setOrdre(?string $ordre): static
    {
        $this->ordre = $ordre;

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

    public function getStatutInitial(): ?string
    {
        return $this->statutInitial;
    }

    public function setStatutInitial(?string $statutInitial): static
    {
        $this->statutInitial = $statutInitial;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getNiveauHierarchique(): ?NiveauHierarchique
    {
        return $this->niveauHierarchique;
    }

    public function setNiveauHierarchique(?NiveauHierarchique $niveauHierarchique): static
    {
        $this->niveauHierarchique = $niveauHierarchique;

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
            $demande->setEtapeDemande($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getEtapeDemande() === $this) {
                $demande->setEtapeDemande(null);
            }
        }

        return $this;
    }
}
