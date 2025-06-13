<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CodeCouleurRepository;
use Doctrine\Common\Collections\Collection;
use App\DataPersister\CodeCouleurDataPersister;
use Doctrine\Common\Collections\ArrayCollection;
use App\DataPersister\CodeCouleurAddDataPersister;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Controller\Api\CodeCouleurBySiteController;
use App\Controller\Api\CodeCouleurToggleController;
use App\DataPersister\CodeCouleurUpdateDataPersister;
use App\Controller\Api\CodeCouleurGetActiveController;
use App\Controller\Api\CodeCouleurGetGlobalActiveController;

#[ORM\Entity(repositoryClass: CodeCouleurRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => 'code_couleur:list'],
        ),
        new Get(
            normalizationContext: ['groups' => 'code_couleur:item'],
            uriTemplate: '/code_couleurs/get-global-active',
            controller: CodeCouleurGetGlobalActiveController::class,
            read: false, // désactive la lecture automatique d'une entité
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Récupérer un code couleur global activé',
                    'description' => 'Cette opération récupère un code couleur global activé.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        new Get(normalizationContext: ['groups' => 'code_couleur:item']),
        new Post(),  
        new Patch(),
        new Delete(),
       
        new Post( 
            uriTemplate: '/code_couleurs/{id}/toggle-active',
            controller: CodeCouleurToggleController::class,
            read: true,
            deserialize: false,
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'Activer/Désactiver un code couleur',
                    'description' => 'Cette opération active ou désactive un code couleur existant.',
                    'responses' => [
                        '200' => ['description' => 'Succès'],
                        '404' => ['description' => 'Non trouvé']
                    ]
                ]
            ]
        ),
        
        new Post( 
            processor: CodeCouleurAddDataPersister::class,
        ),

        new Patch( 
            processor: CodeCouleurUpdateDataPersister::class,
        ),
    ],
)]

class CodeCouleur
{
    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bgColor = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $textColor = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $btnColor = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $colorOne = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $colorTwo = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\ManyToOne(inversedBy: 'codeCouleurs')]
    private ?Site $site = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isGlobal = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isDefault = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $textColorHover = null;

    #[Groups(['code_couleur:list', 'code_couleur:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $btnColorHover = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    public function setBgColor(?string $bgColor): static
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): static
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getBtnColor(): ?string
    {
        return $this->btnColor;
    }

    public function setBtnColor(?string $btnColor): static
    {
        $this->btnColor = $btnColor;

        return $this;
    }

    public function getColorOne(): ?string
    {
        return $this->colorOne;
    }

    public function setColorOne(?string $colorOne): static
    {
        $this->colorOne = $colorOne;

        return $this;
    }

    public function getColorTwo(): ?string
    {
        return $this->colorTwo;
    }

    public function setColorTwo(?string $colorTwo): static
    {
        $this->colorTwo = $colorTwo;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

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

    public function getIsGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    public function setIsGlobal(?bool $isGlobal): static
    {
        $this->isGlobal = $isGlobal;

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getTextColorHover(): ?string
    {
        return $this->textColorHover;
    }

    public function setTextColorHover(?string $textColorHover): static
    {
        $this->textColorHover = $textColorHover;

        return $this;
    }

    public function getBtnColorHover(): ?string
    {
        return $this->btnColorHover;
    }

    public function setBtnColorHover(?string $btnColorHover): static
    {
        $this->btnColorHover = $btnColorHover;

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
