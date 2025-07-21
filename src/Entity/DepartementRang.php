<?php

namespace App\Entity;

use App\Repository\DepartementRangRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRangRepository::class)]
class DepartementRang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $rang = null;

    #[ORM\ManyToOne(inversedBy: 'departementRangs')]
    private ?Departement $departement = null;

    #[ORM\ManyToOne(inversedBy: 'departementRangs')]
    private ?TypeDemande $typeDemande = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(?int $rang): static
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

    public function getTypeDemande(): ?TypeDemande
    {
        return $this->typeDemande;
    }

    public function setTypeDemande(?TypeDemande $typeDemande): static
    {
        $this->typeDemande = $typeDemande;

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
