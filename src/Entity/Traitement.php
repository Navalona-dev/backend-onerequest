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
use App\Repository\TraitementRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TraitementRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'traitement:list']),  
        new Get(normalizationContext: ['groups' => 'traitement:item']),            
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ]
)]
class Traitement
{
    const TYPE_FR = [
        1 => "Traitement interne",
        2 => "Transferer à un departement",
        3 => "Transferer à un site"
    ];

    const TYPE_EN = [
        1 => "Internal processing",
        2 => "Transfer to a department",
        3 => "Transfer to a site"
    ];

    const STATUT_FR = [
        1 => "Envoyé",                // La demande vient d’être envoyée
        2 => "Reçue",                 // Le département ou site destinataire l’a reçue
        3 => "En cours de traitement",// Quelqu’un travaille dessus
        4 => "Transférée",            // La demande a été redirigée ailleurs
        5 => "Traitée",               // Traitement terminé
        6 => "Clôturée",              // Traitement définitivement clos / archivé
    ];

    const STATUT_EN = [
        1 => "Sent",                     // The request has just been sent
        2 => "Received",                 // The department or site has received it
        3 => "In progress",              // Someone is currently working on it
        4 => "Transferred",              // The request has been redirected elsewhere
        5 => "Processed",                // Processing completed
        6 => "Closed",                   // Request definitively closed / archived
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?Demande $demande = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?Site $site = null;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?Departement $departement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['traitement:list', 'traitement:item'])]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDemande(): ?Demande
    {
        return $this->demande;
    }

    public function setDemande(?Demande $demande): static
    {
        $this->demande = $demande;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
