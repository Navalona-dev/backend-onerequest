<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;   
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SiteRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\DataPersister\SiteAddDataPersister;
use Doctrine\Common\Collections\Collection;
use App\Controller\Api\SiteToggleController;
use App\Controller\Api\UserBySiteController;
use App\DataPersister\SiteDeleteDataPersister;
use App\DataPersister\SiteUpdateDataPersister;
use App\Controller\Api\DemandeBySiteController;
use App\Controller\Api\UserConnectedController;
use App\Controller\Api\SiteSetCurrentController;
use App\Controller\Api\SiteUseCurrentController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\Api\AddRegionBySiteController;
use App\Controller\Api\AddCommuneBySiteController;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Controller\Api\CodeCouleurBySiteController;
use App\Controller\Api\DepartementBySiteController;
use App\Controller\Api\TypeDemandeBySiteController;
use App\Controller\Api\SiteToggleDisponibleController;
use App\Controller\Api\RangBySiteAndDepartementController;
use App\Controller\Api\GetSiteActiveAndDisponibleController;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'site:list']), 
        new Get(
            normalizationContext: ['groups' => 'site:item'],
            uriTemplate: '/sites/current',
            controller: SiteUseCurrentController::class,
            read: false,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récuperer un site sélectionné',
                    'description' => 'Cette opération recupère un site selectionné.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'site:item'],
            uriTemplate: '/sites/active-and-disponible',
            controller: GetSiteActiveAndDisponibleController::class,
            read: false,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récuperer la liste de site activé et disponible',
                    'description' => 'Cette opération recupère la liste de site activé et disponible.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'type_demande:list'],
            uriTemplate: '/sites/{id}/type-demandes',
            controller: TypeDemandeBySiteController::class,
            read: true, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'récuperer la liste de type de demande par site et domaine entreprise',
                    'description' => 'Cette opération récupère la liste de type de demande par site et domaine entreprise.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
       
        new Get(normalizationContext: ['groups' => 'site:item']),          
        new Post(),
        new Patch(),
        new Delete(),
        
        new Patch(
            uriTemplate: '/sites/{id}/selected',
            controller: SiteSetCurrentController::class,
            read: false,
            deserialize: false,
            denormalizationContext: ['groups' => ['site:update']],
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Rendre un site sélectionné',
                    'description' => 'Cette opération rend un site selectionné.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Post(
            uriTemplate: '/sites/{id}/toggle-active',
            controller: SiteToggleController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Activer/Désactiver un site',
                    'description' => 'Cette opération active ou désactive un site existant.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'site:item'],
            uriTemplate: '/sites/{id}/users',
            controller: UserBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer utilisateurs par site',
                    'description' => 'Cette opération récupère utilisateurs par site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/sites/{id}/code-couleurs',
            controller: CodeCouleurBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer code couleur par site',
                    'description' => 'Cette opération récupère code couleur par site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/sites/{id}/demandes',
            controller: DemandeBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer demande par site',
                    'description' => 'Cette opération récupère les demandes par site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(
            normalizationContext: ['groups' => 'site:item'],
            uriTemplate: '/sites/{id}/departements',
            controller: DepartementBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer les departements par site',
                    'description' => 'Cette opération récupère les departements par site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(
            normalizationContext: ['groups' => 'demande:item'],
            uriTemplate: '/sites/{id}/departement/{dep}/rangs',
            controller: RangBySiteAndDepartementController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de rang par site et departement',
                    'description' => 'Cette opération récupère la liste de rang par site et departement.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Post(
            uriTemplate: '/sites/{id}/select-region',
            controller: AddRegionBySiteController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Sélectionner ou ajouter une region du site',
                    'description' => 'Cette opération selectionne ou ajout une région d\' un site existant.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Post(
            uriTemplate: '/sites/{id}/select-commune',
            controller: AddCommuneBySiteController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Sélectionner ou ajouter un commune du site',
                    'description' => 'Cette opération selectionne ou ajout un commune d\' un site existant.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Post(
            uriTemplate: '/sites/{id}/toggle-disponible',
            controller: SiteToggleDisponibleController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Disponible/INdisponible un site',
                    'description' => 'Cette opération rend le site disponible ou indisponible.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Post( 
            processor: SiteAddDataPersister::class,
        ),

        new Patch( 
            processor: SiteUpdateDataPersister::class,
        ),

        new Delete( 
            processor: SiteDeleteDataPersister::class,
        ),
       
    ],
)]
class Site
{
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item', 'region:list', 'region:item', 'demande:list', 'demande:item', 'type_demande:list', 'type_demande:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item', 'region:list', 'region:item', 'demande:list', 'demande:item', 'type_demande:list', 'type_demande:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?Entreprise $entreprise = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    /**
     * @var Collection<int, CodeCouleur>
     */
    #[ORM\OneToMany(targetEntity: CodeCouleur::class, mappedBy: 'site')]
    private Collection $codeCouleurs;

    #[ORM\Column(nullable: true)]
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, User>
     */
   
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'site')]
    private Collection $users;

    #[ORM\Column(nullable: true)]
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    private ?bool $isCurrent = null;

    #[Groups(['site:list', 'site:item', 'region:list', 'region:item', 'demande:list', 'demande:item'])]
    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?Region $region = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[Groups(['site:list', 'site:item', 'region:list', 'region:item', 'demande:list', 'demande:item'])]
    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?Commune $commune = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'site')]
    private Collection $demandes;

    /**
     * @var Collection<int, Departement>
     */
    #[ORM\ManyToMany(targetEntity: Departement::class, mappedBy: 'sites')]
    private Collection $departements;

    /**
     * @var Collection<int, TypeDemande>
     */
    #[ORM\ManyToMany(targetEntity: TypeDemande::class, mappedBy: 'sites')]
    private Collection $typeDemandes;

    /**
     * @var Collection<int, DepartementRang>
     */
    #[ORM\OneToMany(targetEntity: DepartementRang::class, mappedBy: 'site')]
    private Collection $departementRangs;

    #[ORM\Column(nullable: true)]
    #[Groups(['site:list', 'site:item', 'code_couleur:list', 'code_couleur:item', 'user:list', 'user:item', 'region:list', 'region:item', 'demande:list', 'demande:item', 'type_demande:list', 'type_demande:item'])]
    private ?bool $isIndisponible = null;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\OneToMany(targetEntity: Traitement::class, mappedBy: 'site')]
    private Collection $traitements;

    public function __construct()
    {
        $this->codeCouleurs = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->departements = new ArrayCollection();
        $this->typeDemandes = new ArrayCollection();
        $this->departementRangs = new ArrayCollection();
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

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
     * @return Collection<int, CodeCouleur>
     */
    public function getCodeCouleurs(): Collection
    {
        return $this->codeCouleurs;
    }

    public function addCodeCouleurs(CodeCouleur $codeCouleurs): static
    {
        if (!$this->codeCouleurs->contains($codeCouleurs)) {
            $this->codeCouleurs->add($codeCouleurs);
            $codeCouleurs->setSite($this);
        }

        return $this;
    }

    public function removeCodeCouleurs(CodeCouleur $codeCouleurs): static
    {
        if ($this->codeCouleurs->removeElement($codeCouleurs)) {
            // set the owning side to null (unless already changed)
            if ($codeCouleurs->getSite() === $this) {
                $codeCouleurs->setSite(null);
            }
        }

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
            $user->setSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSite() === $this) {
                $user->setSite(null);
            }
        }

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(?bool $isCurrent): static
    {
        $this->isCurrent = $isCurrent;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): static
    {
        $this->commune = $commune;

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
            $demande->setSite($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getSite() === $this) {
                $demande->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): static
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
            $departement->addSite($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): static
    {
        if ($this->departements->removeElement($departement)) {
            $departement->removeSite($this);
        }

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
            $typeDemande->addSite($this);
        }

        return $this;
    }

    public function removeTypeDemande(TypeDemande $typeDemande): static
    {
        if ($this->typeDemandes->removeElement($typeDemande)) {
            $typeDemande->removeSite($this);
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
            $departementRang->setSite($this);
        }

        return $this;
    }

    public function removeDepartementRang(DepartementRang $departementRang): static
    {
        if ($this->departementRangs->removeElement($departementRang)) {
            // set the owning side to null (unless already changed)
            if ($departementRang->getSite() === $this) {
                $departementRang->setSite(null);
            }
        }

        return $this;
    }

    public function getIsIndisponible(): ?bool
    {
        return $this->isIndisponible;
    }

    public function setIsIndisponible(?bool $isIndisponible): static
    {
        $this->isIndisponible = $isIndisponible;

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
            $traitement->setSite($this);
        }

        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            // set the owning side to null (unless already changed)
            if ($traitement->getSite() === $this) {
                $traitement->setSite(null);
            }
        }

        return $this;
    }
}
