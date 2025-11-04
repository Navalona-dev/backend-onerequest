<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\Api\ListeDepartementController;
use App\DataPersister\DepartementAddDataPersister;
use Symfony\Component\Serializer\Attribute\Groups;
use App\DataPersister\DepartementDeleteDataPersister;
use App\DataPersister\DepartementUpdateDataPersister;
use App\Controller\Api\DissocieDepartementBySiteController;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Controller\Api\NiveauHierarchiqueByDepartementController;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'departement:list']), 

        new GetCollection(
            normalizationContext: ['groups' => 'departement:list'],
            uriTemplate: '/departements/liste',
            controller: ListeDepartementController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de departement',
                    'description' => 'Cette opération récupère la liste de departement.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Get(normalizationContext: ['groups' => 'departement:item']),  
        new Get(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/departements/{id}/niveau-hierarchique',
            controller: NiveauHierarchiqueByDepartementController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de niveau hierarchique par departement',
                    'description' => 'Cette opération récupère la liste de niveau hierarchique par departement.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        new Delete(
            normalizationContext: ['groups' => 'type_demande:list'],
            uriTemplate: '/departements/{id}/site/{siteId}/dissocie',
            controller: DissocieDepartementBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'dissocié le departement du site',
                    'description' => 'Cette opération dissocie le departement du site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),       
        new Post(),
        new Patch(),
        new Delete(),
        new Post( 
            processor: DepartementAddDataPersister::class,
        ),
        new Patch( 
            processor: DepartementUpdateDataPersister::class,
        ),

        new Delete( 
            processor: DepartementDeleteDataPersister::class,
        ),
    ]
)]
class Departement
{
    #[Groups(['departement:list', 'departement:item', 'niveau_hierarchique:list', 'niveau_hierarchique:item', 'traitement:list', 'traitement:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['departement:list', 'departement:item', 'niveau_hierarchique:list', 'niveau_hierarchique:item', 'traitement:list', 'traitement:item'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['departement:list', 'departement:item'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['departement:list', 'departement:item'])]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['departement:list', 'departement:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['departement:list', 'departement:item'])]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, inversedBy: 'departements')]
    private Collection $sites;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'departements')]
    private ?self $departement = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'departement')]
    private Collection $departements;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['departement:list', 'departement:item', 'niveau_hierarchique:list', 'niveau_hierarchique:item', 'traitement:list', 'traitement:item'])]
    private ?string $nomEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['departement:list', 'departement:item'])]
    private ?string $descriptionEn = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'departement')]
    private Collection $users;

    /**
     * @var Collection<int, NiveauHierarchiqueRang>
     */
    #[ORM\OneToMany(targetEntity: NiveauHierarchiqueRang::class, mappedBy: 'departement')]
    private Collection $niveauHierarchiqueRangs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    /**
     * @var Collection<int, NiveauHierarchique>
     */
    #[ORM\ManyToMany(targetEntity: NiveauHierarchique::class, mappedBy: 'departements')]
    #[Groups(['departement:list', 'departement:item'])]
    private Collection $niveauHierarchiques;

    /**
     * @var Collection<int, DepartementRang>
     */
    #[ORM\OneToMany(targetEntity: DepartementRang::class, mappedBy: 'departement')]
    #[Groups(['departement:list', 'departement:item'])]
    private Collection $departementRangs;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'departement')]
    private Collection $demandes;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\OneToMany(targetEntity: Traitement::class, mappedBy: 'departement')]
    private Collection $traitements;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->departements = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->niveauHierarchiqueRangs = new ArrayCollection();
        $this->niveauHierarchiques = new ArrayCollection();
        $this->departementRangs = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->traitements = new ArrayCollection();
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

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
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        $this->sites->removeElement($site);

        return $this;
    }

    public function getDepartement(): ?self
    {
        return $this->departement;
    }

    public function setDepartement(?self $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(self $departement): static
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
            $departement->setDepartement($this);
        }

        return $this;
    }

    public function removeDepartement(self $departement): static
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getDepartement() === $this) {
                $departement->setDepartement(null);
            }
        }

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

  

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setDepartement($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDepartement() === $this) {
                $user->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NiveauHierarchiqueRang>
     */
    public function getNiveauHierarchiqueRangs(): Collection
    {
        return $this->niveauHierarchiqueRangs;
    }

    public function addNiveauHierarchiqueRang(NiveauHierarchiqueRang $niveauHierarchiqueRang): static
    {
        if (!$this->niveauHierarchiqueRangs->contains($niveauHierarchiqueRang)) {
            $this->niveauHierarchiqueRangs->add($niveauHierarchiqueRang);
            $niveauHierarchiqueRang->setDepartement($this);
        }

        return $this;
    }

    public function removeNiveauHierarchiqueRang(NiveauHierarchiqueRang $niveauHierarchiqueRang): static
    {
        if ($this->niveauHierarchiqueRangs->removeElement($niveauHierarchiqueRang)) {
            // set the owning side to null (unless already changed)
            if ($niveauHierarchiqueRang->getDepartement() === $this) {
                $niveauHierarchiqueRang->setDepartement(null);
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

    /**
     * @return Collection<int, NiveauHierarchique>
     */
    public function getNiveauHierarchiques(): Collection
    {
        return $this->niveauHierarchiques;
    }

    public function addNiveauHierarchique(NiveauHierarchique $niveauHierarchique): static
    {
        if (!$this->niveauHierarchiques->contains($niveauHierarchique)) {
            $this->niveauHierarchiques->add($niveauHierarchique);
            $niveauHierarchique->addDepartement($this);
        }

        return $this;
    }

    public function removeNiveauHierarchique(NiveauHierarchique $niveauHierarchique): static
    {
        if ($this->niveauHierarchiques->removeElement($niveauHierarchique)) {
            $niveauHierarchique->removeDepartement($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DepartementRang>
     */
    public function getDepartementRangs(): Collection
    {
        return $this->departementRangs;
    }

    public function addDepartementRang(DepartementRang $departementRang): static
    {
        if (!$this->departementRangs->contains($departementRang)) {
            $this->departementRangs->add($departementRang);
            $departementRang->setDepartement($this);
        }

        return $this;
    }

    public function removeDepartementRang(DepartementRang $departementRang): static
    {
        if ($this->departementRangs->removeElement($departementRang)) {
            // set the owning side to null (unless already changed)
            if ($departementRang->getDepartement() === $this) {
                $departementRang->setDepartement(null);
            }
        }

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
            $demande->setDepartement($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getDepartement() === $this) {
                $demande->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Traitement>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    public function addTraitement(Traitement $traitement): static
    {
        if (!$this->traitements->contains($traitement)) {
            $this->traitements->add($traitement);
            $traitement->setDepartement($this);
        }

        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            // set the owning side to null (unless already changed)
            if ($traitement->getDepartement() === $this) {
                $traitement->setDepartement(null);
            }
        }

        return $this;
    }

}
