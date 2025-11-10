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
use App\Controller\Api\TypeDemandeBySiteController;
use App\Controller\Api\EtapeByTypeDemandeController;
use App\DataPersister\DeleteTypeDemandeDataPersister;
use App\DataPersister\TypeDemandeUpdateDataPersister;
use App\Controller\Api\DissociateTypeDemandeBySiteController;
use App\Controller\Api\DossierAFournirByTypeDemandeController;
use App\Controller\Api\TypeDemandeByDomaineEntrepriseController;

#[ORM\Entity(repositoryClass: TypeDemandeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'type_demande:list']), 
        new Get(
            normalizationContext: ['groups' => 'type_demande:list'],
            uriTemplate: '/type_demandes/liste-by-entreprise',
            controller: TypeDemandeByDomaineEntrepriseController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'récuperer la liste de type de demande par entreprise',
                    'description' => 'Cette opération récupère la liste de demande par entreprise.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        new Get(
            normalizationContext: ['groups' => 'type_demande:list'],
            uriTemplate: '/type_demandes/{id}/dossiers-a-fournir',
            controller: DossierAFournirByTypeDemandeController::class,
            read: true, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'récuperer la liste de dossier à fournir',
                    'description' => 'Cette opération récupère la liste de dossier à fournir.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        
        new Get(normalizationContext: ['groups' => 'type_demande:item']), 

        new Get(
            normalizationContext: ['groups' => 'type_demande:list'],
            uriTemplate: '/type_demandes/{id}/site/{site}/etapes',
            controller: EtapeByTypeDemandeController::class,
            read: true, 
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'récuperer la liste des étapes par type de demande',
                    'description' => 'Cette opération récupère la liste des étapes par type de demande.',
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
        new Delete(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/type_demandes/{idType}/site/{idSite}/dissocier',
            controller: DissociateTypeDemandeBySiteController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Dissocier le type de deamnde par site',
                    'description' => 'Cette opération dissocie le type de deamnde par site.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ), 
        new Post( 
            processor: TypeDemandeAddDataPersister::class,
        ),
        new Patch( 
            processor: TypeDemandeUpdateDataPersister::class,
        ),
        new Delete( 
            processor: DeleteTypeDemandeDataPersister::class,
        ),
    ],
    paginationEnabled: false
)]
class TypeDemande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item', 'type_demande_etape:list', 'type_demande_etape:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item', 'traitement:list', 'traitement:item', 'type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'typeDemandes')]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
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

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item', 'traitement:list', 'traitement:item', 'type_demande_etape:list', 'type_demande_etape:item'])]
    private ?string $nomEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['type_demande:list', 'type_demande:item', 'demande:list', 'demande:item'])]
    private ?string $descriptionEn = null;

    /**
     * @var Collection<int, DepartementRang>
     */
    #[ORM\OneToMany(targetEntity: DepartementRang::class, mappedBy: 'typeDemande')]
    private Collection $departementRangs;

    /**
     * @var Collection<int, Site>
     */
    #[ORM\ManyToMany(targetEntity: Site::class, inversedBy: 'typeDemandes')]
    #[Groups(['type_demande:list', 'type_demande:item', 'site:list', 'site:item'])]
    private Collection $sites;

    /**
     * @var Collection<int, NiveauHierarchiqueRang>
     */
    #[ORM\OneToMany(targetEntity: NiveauHierarchiqueRang::class, mappedBy: 'typeDemande')]
    private Collection $niveauHierarchiqueRangs;

    /**
     * @var Collection<int, TypeDemandeEtape>
     */
    #[ORM\OneToMany(targetEntity: TypeDemandeEtape::class, mappedBy: 'typeDemande')]
    private Collection $typeDemandeEtapes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->dossierAFournirs = new ArrayCollection();
        $this->departementRangs = new ArrayCollection();
        $this->sites = new ArrayCollection();
        $this->niveauHierarchiqueRangs = new ArrayCollection();
        $this->typeDemandeEtapes = new ArrayCollection();
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

    public function getIsActive(): ?bool
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
            $departementRang->setTypeDemande($this);
        }

        return $this;
    }

    public function removeDepartementRang(DepartementRang $departementRang): static
    {
        if ($this->departementRangs->removeElement($departementRang)) {
            // set the owning side to null (unless already changed)
            if ($departementRang->getTypeDemande() === $this) {
                $departementRang->setTypeDemande(null);
            }
        }

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
            $niveauHierarchiqueRang->setTypeDemande($this);
        }

        return $this;
    }

    public function removeNiveauHierarchiqueRang(NiveauHierarchiqueRang $niveauHierarchiqueRang): static
    {
        if ($this->niveauHierarchiqueRangs->removeElement($niveauHierarchiqueRang)) {
            // set the owning side to null (unless already changed)
            if ($niveauHierarchiqueRang->getTypeDemande() === $this) {
                $niveauHierarchiqueRang->setTypeDemande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeDemandeEtape>
     */
    public function getTypeDemandeEtapes(): Collection
    {
        return $this->typeDemandeEtapes;
    }

    public function addTypeDemandeEtape(TypeDemandeEtape $typeDemandeEtape): static
    {
        if (!$this->typeDemandeEtapes->contains($typeDemandeEtape)) {
            $this->typeDemandeEtapes->add($typeDemandeEtape);
            $typeDemandeEtape->setTypeDemande($this);
        }

        return $this;
    }

    public function removeTypeDemandeEtape(TypeDemandeEtape $typeDemandeEtape): static
    {
        if ($this->typeDemandeEtapes->removeElement($typeDemandeEtape)) {
            // set the owning side to null (unless already changed)
            if ($typeDemandeEtape->getTypeDemande() === $this) {
                $typeDemandeEtape->setTypeDemande(null);
            }
        }

        return $this;
    }

}
