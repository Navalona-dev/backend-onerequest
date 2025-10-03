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
use Doctrine\Common\Collections\Collection;
use App\Controller\Api\RangsByNiveauController;
use App\Repository\NiveauHierarchiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\DataPersister\NiveauHierarchiqueAddDataPersister;
use App\Controller\Api\RangByNiveauAndDepartementController;
use App\DataPersister\NiveauHierarchiqueDeleteDataPersister;
use App\DataPersister\NiveauHierarchiqueUpdateDataPersister;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Controller\Api\DepartementByNiveauHierarchiqueController;
use App\Controller\Api\DeleteNiveauHierarchiqueByDepartementController;

#[ORM\Entity(repositoryClass: NiveauHierarchiqueRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'niveau_hierarchique:list']), 
        new Get(normalizationContext: ['groups' => 'niveau_hierarchique:item']),   
        new Get(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/niveau_hierarchiques/{id}/departements',
            controller: DepartementByNiveauHierarchiqueController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de departement par niveau hierarchique',
                    'description' => 'Cette opération récupère la liste de departement par niveau hierarchique.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        new Get(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/niveau_hierarchiques/{id}/departement/{dep}',
            controller: RangByNiveauAndDepartementController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer le rang par departement et niveau hierarchique',
                    'description' => 'Cette opération récupère le rang par departement et niveau hierarchique.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),        
        new Get(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/niveau_hierarchiques/{id}/departement/{dep}/rangs',
            controller: RangsByNiveauController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer le rang par niveau hierarchique',
                    'description' => 'Cette opération récupère le rang par niveau hierarchique.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),       
        new Post(),
        new Patch(),
        new Delete(
            normalizationContext: ['groups' => 'departement:item'],
            uriTemplate: '/niveau_hierarchiques/{id}/departements/{dep}/dissocier',
            controller: DeleteNiveauHierarchiqueByDepartementController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Dissocier le niveau hierarchique dans le departement',
                    'description' => 'Cette opération dissocie le niveau hierarchique dans le departement.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),   
        new Post( 
            processor: NiveauHierarchiqueAddDataPersister::class,
        ),
        new Patch( 
            processor: NiveauHierarchiqueUpdateDataPersister::class,
        ),
        new Delete( 
            processor: NiveauHierarchiqueDeleteDataPersister::class,
        ),
        
    ]
)]
class NiveauHierarchique
{
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item', 'departement:list', 'departement:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?string $descriptionEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?string $nomEn = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'niveauHierarchique')]
    private Collection $user;

    #[ORM\Column(nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, NiveauHierarchiqueRang>
     */
    #[ORM\OneToMany(targetEntity: NiveauHierarchiqueRang::class, mappedBy: 'niveauHierarchique')]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private Collection $niveauHierarchiqueRangs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    /**
     * @var Collection<int, Departement>
     */
    #[ORM\ManyToMany(targetEntity: Departement::class, inversedBy: 'niveauHierarchiques')]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private Collection $departements;

    #[ORM\ManyToOne(inversedBy: 'niveauHierarchiques')]
    #[Groups(['niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?Privilege $privilege = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->niveauHierarchiqueRangs = new ArrayCollection();
        $this->departements = new ArrayCollection();
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

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(?string $descriptionEn): static
    {
        $this->descriptionEn = $descriptionEn;

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
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setNiveauHierarchique($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getNiveauHierarchique() === $this) {
                $user->setNiveauHierarchique(null);
            }
        }

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
            $niveauHierarchiqueRang->setNiveauHierarchique($this);
        }

        return $this;
    }

    public function removeNiveauHierarchiqueRang(NiveauHierarchiqueRang $niveauHierarchiqueRang): static
    {
        if ($this->niveauHierarchiqueRangs->removeElement($niveauHierarchiqueRang)) {
            // set the owning side to null (unless already changed)
            if ($niveauHierarchiqueRang->getNiveauHierarchique() === $this) {
                $niveauHierarchiqueRang->setNiveauHierarchique(null);
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
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): static
    {
        $this->departements->removeElement($departement);

        return $this;
    }

    public function getPrivilege(): ?Privilege
    {
        return $this->privilege;
    }

    public function setPrivilege(?Privilege $privilege): static
    {
        $this->privilege = $privilege;

        return $this;
    }
}
