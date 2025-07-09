<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;  
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\DemandeRepository;
use ApiPlatform\Metadata\GetCollection;
use App\DataPersister\DemandeAddDataPersister;
use App\DataPersister\DemandeUpdateDataPersister;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Controller\Api\ListeStatutDemandeController;


#[ORM\Entity(repositoryClass: DemandeRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'demande:list']), 
        new Get(
            normalizationContext: ['groups' => 'demande:item'],
            uriTemplate: '/demandes/statut',
            controller: ListeStatutDemandeController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer la liste de statut de demande',
                    'description' => 'Cette opération récupère la liste de statut de demande.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(normalizationContext: ['groups' => 'demande:item']),            
        new Post(),
        new Patch(),
        new Post( 
            deserialize: false,
            processor: DemandeAddDataPersister::class,
        ),
        new Patch( 
            deserialize: false,
            processor: DemandeUpdateDataPersister::class,
        ),
    ]
)]
class Demande
{
    const STATUT = [
        1 => "En attente",
        2 => "En cours",
        3 => "Annullée",
        4 => "Réfusée",
        5 => "Validée",
        6 => "Accepté",
        7 => "Brouillon",
        8 => "Soumise",
        9 => "Terminée",
        10 => "En pause",
        11 => "Erreur",
        12 => "Clôturée",
    ];

    #[Groups(['demande:list', 'demande:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[Groups(['demande:list', 'demande:item'])]
    private ?TypeDemande $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demande:list', 'demande:item'])]
    private ?string $statut = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demande:list', 'demande:item'])]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['demande:list', 'demande:item'])]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[Groups(['demande:list', 'demande:item'])]
    private ?User $demandeur = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[Groups(['demande:list', 'demande:item'])]
    private ?Site $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demande:list', 'demande:item'])]
    private ?string $fichier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeDemande
    {
        return $this->type;
    }

    public function setType(?TypeDemande $type): static
    {
        $this->type = $type;

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

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): static
    {
        $this->demandeur = $demandeur;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }
}
