<?php

namespace App\Controller\Api;

use App\Entity\Site;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use App\Repository\TutorielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HeroSectionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TutorielListeController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private RequestStack $requestStack
    ) {}
    
    public function __invoke(TutorielRepository $tutorielRepo, EntityManagerInterface $em): JsonResponse
    {

        $tutoriels = $tutorielRepo->findBy(['isActive' => true]);

        $tutorielTab = [];

        foreach ($tutoriels as $tutoriel) {

            $nomFichier = $tutoriel->getFichier();
            $nomVideo = $tutoriel->getVideo();

            $request = $this->requestStack->getCurrentRequest();
            $baseUrl = $request->getSchemeAndHttpHost(); 
            $relativePathFichier = "/uploads/tutoriel/fichier" . "/" . $nomFichier;
            $relativePathVideo = "/uploads/tutoriel/video" . "/" . $nomFichier;

            $tutorielTab[] = [
                'id' => $tutoriel->getId(),
                'titleFr' => $tutoriel->getTitleFr(),
                'titleEn' => $tutoriel->getTitleEn(),
                'descriptionFr' => $tutoriel->getDescriptionFr(),
                'descriptionEn' => $tutoriel->getDescriptionEn(),
                'video' => $baseUrl . $relativePathVideo,
                'fichier' => $baseUrl . $relativePathFichier,
                'isActive' => $tutoriel->getIsActive(),
                'icon' => $tutoriel->getIcon()
                
            ];
        }

        return new JsonResponse($tutorielTab);
    }
}
