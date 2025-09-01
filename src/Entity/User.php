<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\ApiResource\Filter\RoleFilter;
use ApiPlatform\Metadata\GetCollection;
use App\DataPersister\UserDataPersister;
use App\DataPersister\AddUserDataPersister;
use App\DataPersister\UserAddDataPersister;
use Doctrine\Common\Collections\Collection;
use App\Controller\Api\UserRegisterController;
use App\DataPersister\UserDeleteDataPersister;
use App\DataPersister\UserUpdateDataPersister;
use App\Controller\Api\DemandeByUserController;
use App\Controller\Api\UserConnectedController;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\Api\GetLangueByUserController;
use App\Controller\Api\SetLangueByUserController;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'user:list', 'enable_max_depth' => true]), 
        new Get(normalizationContext: ['groups' => 'user:item']),          
        new Post(),
        new Post(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/users/register',
            controller: UserRegisterController::class,
            read: true, // désactive la lecture automatique d'une entité
            deserialize: true,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Créer un compte demandeur',
                    'description' => 'Cette opération crée un compte pour un demandeur.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Patch(),
        new Delete(),
        new Get(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/users/{email}/get-user-admin-connected',
            controller: UserConnectedController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer un utilisateur connecté',
                    'description' => 'Cette opération récupère un utilisateur connecté.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/users/{email}/get-langue',
            controller: GetLangueByUserController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer une langue par utilisateur ',
                    'description' => 'Cette opération récupère une langue par utilisateur .',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Get(
            normalizationContext: ['groups' => 'user:item'],
            uriTemplate: '/users/{id}/demandes',
            controller: DemandeByUserController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de demandes par utilisateur',
                    'description' => 'Cette opération récupère une liste de demande par utilisateur.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Patch(
            uriTemplate: '/users/{id}/set-langue',
            controller: SetLangueByUserController::class,
            read: false,
            deserialize: false,
            denormalizationContext: ['groups' => ['user:update']],
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Ajouter langue à un utilisateur',
                    'description' => 'Cette opération ajout une langue à un utilisateur.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),

        new Post( 
            processor: UserAddDataPersister::class,
        ),

        new Patch( 
            processor: UserUpdateDataPersister::class,
        ),

        new Delete( 
            processor: UserDeleteDataPersister::class,
        ),
        
    ]
)]
#[ApiFilter(RoleFilter::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[Groups(['user:list', 'user:item', 'site:list', 'site:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile = null;

    #[Groups(['user:list', 'user:item'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isSuperAdmin = null;

    #[Groups(['user:list', 'user:item'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Site $site = null;

    /**
     * @var Collection<int, Privilege>
     */
    #[Groups(['user:list', 'user:item'])]
    #[ORM\ManyToMany(targetEntity: Privilege::class, inversedBy: 'users')]
    private Collection $privileges;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'demandeur')]
    private Collection $demandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[Groups(['user:list', 'user:item', 'langue:list', 'langue:item'])]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Langue $langue = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?NiveauHierarchique $niveauHierarchique = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Departement $departement = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\ManyToMany(targetEntity: Demande::class, mappedBy: 'responsables')]
    private Collection $demandesTraitees;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\OneToMany(targetEntity: Traitement::class, mappedBy: 'user')]
    private Collection $traitements;

    public function __construct()
    {
        $this->privileges = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->demandesTraitees = new ArrayCollection();
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

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
    
        // Garantie que chaque utilisateur a au moins ROLE_USER
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
    
        return array_unique($roles);
    }
    

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

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

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // Cette méthode est utilisée pour effacer des données sensibles.
        // Par exemple, si tu stockes un mot de passe en clair temporairement.
        // Ici on ne fait rien car ce n’est pas nécessaire.
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

    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(?bool $isSuperAdmin): static
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    /**
     * @return Collection<int, Privilege>
     */
    public function getPrivileges(): Collection
    {
        return $this->privileges;
    }

    public function addPrivilege(Privilege $privilege): static
    {
        if (!$this->privileges->contains($privilege)) {
            $this->privileges->add($privilege);
        }

        return $this;
    }

    public function removePrivilege(Privilege $privilege): static
    {
        $this->privileges->removeElement($privilege);

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
            $demande->setDemandeur($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getDemandeur() === $this) {
                $demande->setDemandeur(null);
            }
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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

    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): static
    {
        $this->langue = $langue;

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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandesTraitees(): Collection
    {
        return $this->demandesTraitees;
    }

    public function addDemandesTraitee(Demande $demandesTraitee): static
    {
        if (!$this->demandesTraitees->contains($demandesTraitee)) {
            $this->demandesTraitees->add($demandesTraitee);
            $demandesTraitee->addResponsable($this);
        }

        return $this;
    }

    public function removeDemandesTraitee(Demande $demandesTraitee): static
    {
        if ($this->demandesTraitees->removeElement($demandesTraitee)) {
            $demandesTraitee->removeResponsable($this);
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
            $traitement->setUser($this);
        }

        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            // set the owning side to null (unless already changed)
            if ($traitement->getUser() === $this) {
                $traitement->setUser(null);
            }
        }

        return $this;
    }

    
}
