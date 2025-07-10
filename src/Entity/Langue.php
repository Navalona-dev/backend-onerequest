<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LangueRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Api\GetLangueActiveController;
use App\Controller\Api\GetLanguePublicController;
use App\Controller\Api\SetLangueActiveController;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'langue:list']), 
        new Get(
            normalizationContext: ['groups' => 'langue:list'],
            uriTemplate: '/langues/get-is-active',
            controller: GetLangueActiveController::class,
            read: false,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récuperer une langue activée',
                    'description' => 'Cette opération recupère une langue activée.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        new Get(
            normalizationContext: ['groups' => 'langue:list'],
            uriTemplate: '/langues/public',
            controller: GetLanguePublicController::class,
            read: false,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récuperer la liste de langue',
                    'description' => 'Cette opération recupère la liste de langue.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),  
        new Get(normalizationContext: ['groups' => 'langue:item']),  
        
        new Post(),
        new Patch(),
        new Patch(
            uriTemplate: '/langues/{id}/set-active',
            controller: SetLangueActiveController::class,
            read: false,
            deserialize: false,
            denormalizationContext: ['groups' => ['langue:update']],
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Rendre une langue activée',
                    'description' => 'Cette opération rend une langue activée.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Delete(),

        
        
    ]
)]
class Langue
{
    #[Groups(['langue:list', 'langue:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['langue:list', 'langue:item'])]
    private ?string $titleFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['langue:list', 'langue:item'])]
    private ?string $titleEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['langue:list', 'langue:item'])]
    private ?string $icon = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['langue:list', 'langue:item'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['langue:list', 'langue:item'])]
    private ?string $indice = null;

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

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(?string $titleEn): static
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

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

    public function getIndice(): ?string
    {
        return $this->indice;
    }

    public function setIndice(?string $indice): static
    {
        $this->indice = $indice;

        return $this;
    }
}
