<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\NiveauHierarchiqueRangRepository;
use App\DataPersister\NiveauHierarchiqueRangAddDataPersister;

#[ORM\Entity(repositoryClass: NiveauHierarchiqueRangRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'niveau_hierarchique_rang:list']), 
        new Get(normalizationContext: ['groups' => 'niveau_hierarchique_rang:item']),           
        new Post(),
        new Patch(),
        new Delete(),
        new Post( 
            processor: NiveauHierarchiqueRangAddDataPersister::class,
        ),
        
    ]
)]
class NiveauHierarchiqueRang
{
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item', 'niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item', 'niveau_hierarchique:list', 'niveau_hierarchique:item'])]
    private ?int $rang = null;

    #[ORM\ManyToOne(inversedBy: 'niveauHierarchiqueRangs')]
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item'])]
    private ?Departement $departement = null;

    #[ORM\ManyToOne(inversedBy: 'niveauHierarchiqueRangs')]
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item'])]
    private ?NiveauHierarchique $niveauHierarchique = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['niveau_hierarchique_rang:list', 'niveau_hierarchique_rang:item'])]
    private ?\DateTime $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRang(): ?string
    {
        return $this->rang;
    }

    public function setRang(?string $rang): static
    {
        $this->rang = $rang;

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

    public function getNiveauHierarchique(): ?NiveauHierarchique
    {
        return $this->niveauHierarchique;
    }

    public function setNiveauHierarchique(?NiveauHierarchique $niveauHierarchique): static
    {
        $this->niveauHierarchique = $niveauHierarchique;

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
}
