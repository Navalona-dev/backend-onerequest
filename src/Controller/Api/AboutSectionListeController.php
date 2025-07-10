<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HeroSectionRepository;
use App\Repository\AboutSectionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AboutSectionListeController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(AboutSectionRepository $aboutRepo, EntityManagerInterface $em): JsonResponse
    {

        $abouts = $aboutRepo->findAll();

        $aboutTab = [];

        foreach ($abouts as $about) {

            $aboutTab[] = [
                'id' => $about->getId(),
                'titleFr' => $about->getTitleFr(),
                'titleEn' => $about->getTitleEn(),
                'descriptionFr' => $about->getDescriptionFr(),
                'descriptionEn' => $about->getDescriptionEn(),
                
            ];
        }

        return new JsonResponse($aboutTab);
    }
}
