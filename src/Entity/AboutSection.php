<?php

namespace App\Entity;

use App\Repository\AboutSectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AboutSectionRepository::class)]
class AboutSection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleFr = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionFr = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(?string $descriptionEn): static
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->titleFr;
    }

    public function setTitleFr(?string $titleFr): static
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->descriptionFr;
    }

    public function setDescriptionFr(?string $descriptionFr): static
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }
}
