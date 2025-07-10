<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HeroSectionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HeroSectionListeController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(HeroSectionRepository $heroRepo, EntityManagerInterface $em): JsonResponse
    {

        $heros = $heroRepo->findAll();

        $heroTab = [];

        foreach ($heros as $hero) {
            $nomFichier = $hero->getBgImage();

            $request = $this->requestStack->getCurrentRequest();
            $baseUrl = $request->getSchemeAndHttpHost(); 
            $relativePath = "/uploads/heros" . "/" . $nomFichier;

            $heroTab[] = [
                'id' => $hero->getId(),
                'titleFr' => $hero->getTitleFr(),
                'titleEn' => $hero->getTitleEn(),
                'descriptionFr' => $hero->getDescriptionFr(),
                'descriptionEn' => $hero->getDescriptionEn(),
                'bgImage' => $baseUrl . $relativePath,
                
            ];
        }

        return new JsonResponse($heroTab);
    }
}
