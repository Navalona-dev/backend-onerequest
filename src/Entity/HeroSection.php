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
use App\Repository\HeroSectionRepository;
use App\Controller\Api\HeroSectionListeController;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: HeroSectionRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'hero_section:list']), 
        new Get(
            normalizationContext: ['groups' => 'hero_section:list'],
            uriTemplate: '/hero_sections/liste',
            controller: HeroSectionListeController::class,
            read: false,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récuperer la liste de hero section',
                    'description' => 'Cette opération recupère la liste de hero section.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(normalizationContext: ['groups' => 'hero_section:item']),            
        new Post(),
        new Patch(),
        new Delete(),
        
    ]
)]
class HeroSection
{
    #[Groups(['hero_section:list', 'hero_section:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hero_section:list', 'hero_section:item'])]
    private ?string $titleFr = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hero_section:list', 'hero_section:item'])]
    private ?string $descriptionFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hero_section:list', 'hero_section:item'])]
    private ?string $bgImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hero_section:list', 'hero_section:item'])]
    private ?string $titleEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hero_section:list', 'hero_section:item'])]
    private ?string $descriptionEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBgImage(): ?string
    {
        return $this->bgImage;
    }

    public function setBgImage(?string $bgImage): static
    {
        $this->bgImage = $bgImage;

        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }
}
